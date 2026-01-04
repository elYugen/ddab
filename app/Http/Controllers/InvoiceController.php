<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // --- TARIFS CONVENTIONNELS (Simplifiés pour v1) ---
    // En prod, ces tarifs devraient être en base de données ou config
    const RATES = [
        'AMBULANCE' => [
            'FORFAIT_DEPARTEMENTAL' => 58.67, // Forfait départemental
            'FORFAIT_AGGLO' => 64.90, // Forfait agglo/Prise en charge
            'KM' => 2.37, // Tarif au km
        ],
        'VSL' => [
            'FORFAIT' => 14.80, // Forfait A
            'KM' => 1.05, // Tarif au km
        ],
        'MAJORATIONS' => [
            'NUIT' => 0.50, // +50% (20h-8h)
            'DIMANCHE' => 0.50, // +50%
        ],
        'TVA' => 0.00 // Exonéré de TVA pour le transport sanitaire (souvent) ou 2.1% / 10% selon les cas. Mettons 0 pour simplifier ou 10 si besoin.
    ];

    public function index()
    {
        $user = Auth::user();
        $company = $user->companies()->wherePivot('is_active', true)->first();

        if (!$company && $user->companies()->count() > 0) {
            $company = $user->companies()->first();
        }

        if (!$company) {
            return view('dashboard.invoice', ['invoices' => []]);
        }

        // Récupérer les factures
        $invoices = Invoice::with(['patient', 'transport'])
            ->where('company_id', $company->id)
            ->latest()
            ->get();
            
        // Récupérer les transports terminés mais non facturés
        $pendingTransports = Transport::where('company_id', $company->id)
            ->whereDoesntHave('invoice')
            ->where('transport_date', '<=', now())
            ->latest()
            ->get();

        return view('dashboard.invoice', compact('invoices', 'pendingTransports'));
    }

    /**
     * Générer une facture depuis un transport
     */
    public function createFromTransport(Transport $transport)
    {
        $this->authorizeTransport($transport);

        if ($transport->invoice) {
            return redirect()->route('dashboard.invoice.show', $transport->invoice->id)
                ->with('warning', 'Une facture existe déjà pour ce transport.');
        }

        // Calcul des coûts
        $price = $this->calculateTransportPrice($transport);
        
        $lines = $price['lines'];
        $total = $price['total'];

        // Création de la facture brouillon
        $invoice = Invoice::create([
            'company_id' => $transport->company_id,
            'transport_id' => $transport->id,
            'patient_id' => $transport->patient_id,
            'invoice_number' => $this->generateInvoiceNumber($transport->company_id),
            'status' => 'DRAFT',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $total,
            'tax_amount' => 0, // Exonéré art 261-4-2 CGI
            'total_amount' => $total,
        ]);

        // Création des lignes
        foreach ($lines as $line) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'code' => $line['code'],
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'tax_rate' => 0,
                'total' => $line['total'],
            ]);
        }

        return redirect()->route('dashboard.invoice.index')
            ->with('success', 'Facture générée avec succès !');
    }

    /**
     * Voir une facture
     */
    public function show(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $company = $invoice->company;
        
        // Items are loaded via relationship automatically if we use 'with' or lazy load
        $invoice->load(['items', 'patient', 'transport', 'company']);

        if (request()->ajax()) {
            return view('dashboard.invoice.content', compact('invoice', 'company'));
        }

        return view('dashboard.invoice.show', compact('invoice', 'company'));
    }

    /**
     * Mettre à jour une facture (Statut)
     */
    /**
     * Éditer une facture
     */
    public function edit(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        
        // Restriction
        if (in_array($invoice->status, ['PAID', 'SENT'])) {
             // For AJAX/Modal
             if(request()->ajax()) {
                 return response('<div class="text-red-500 p-4">Impossible de modifier une facture payée ou envoyée.</div>', 403);
             }
             return back()->with('warning', 'Impossible de modifier une facture payée ou envoyée.');
        }

        $invoice->load('items', 'patient');
        
        if (request()->ajax()) {
            return view('dashboard.invoice.edit-content', compact('invoice'));
        }

        return view('dashboard.invoice.edit', compact('invoice'));
    }

    /**
     * Mettre à jour une facture
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        
        // Restriction
        if (in_array($invoice->status, ['PAID', 'SENT']) && !$request->has('status')) {
             return back()->with('warning', 'Impossible de modifier une facture payée ou envoyée.');
        }
        
        // Si c'est juste une mise à jour de statut via la liste/modal
        if ($request->has('status') && !$request->has('items')) {
            $invoice->update(['status' => $request->status]);
            return back()->with('success', 'Statut de la facture mis à jour.');
        }

        // Sinon, mise à jour complète (Edition)
        $request->validate([
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:DRAFT,SENT,PAID,CANCELLED',
            'items' => 'array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $currentItems = $invoice->items->keyBy('id');
        $processedItemIds = [];

        // 1. Process items (Update or Create)
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $lineTotal = $itemData['quantity'] * $itemData['unit_price'];
                $subtotal += $lineTotal;

                if (isset($itemData['id']) && $currentItems->has($itemData['id'])) {
                    // Update existing
                    $currentItems[$itemData['id']]->update([
                        'description' => $itemData['description'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total' => $lineTotal,
                    ]);
                    $processedItemIds[] = $itemData['id'];
                } else {
                    // Create new
                    $newItem = InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $itemData['description'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total' => $lineTotal,
                        'tax_rate' => 0, // Default 0 for now
                    ]);
                    $processedItemIds[] = $newItem->id;
                }
            }
        }

        // 2. Delete removed items
        InvoiceItem::where('invoice_id', $invoice->id)
            ->whereNotIn('id', $processedItemIds)
            ->delete();

        // 3. Update Invoice Totals & Info
        $invoice->update([
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'subtotal' => $subtotal,
            'tax_amount' => 0, // Still 0 logic for simplicity
            'total_amount' => $subtotal,
        ]);

        return redirect()->route('dashboard.invoice.index')->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Supprimer une facture
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        
        $invoice->delete();

        return redirect()->route('dashboard.invoice.index')
            ->with('success', 'Facture supprimée. Le transport est de nouveau disponible pour facturation.');
    }

    /**
     * Calcul du prix ARS Friendly
     */
    private function calculateTransportPrice(Transport $transport)
    {
        $lines = [];
        $total = 0;
        $km = $transport->distance_km ?? 0;
        
        // Détermination du tarif jour/nuit et semaine/weekend
        $transportDate = Carbon::parse($transport->transport_date)->format('Y-m-d');
        $date = Carbon::parse($transportDate . ' ' . $transport->start_time);
        $isNight = $date->hour >= 20 || $date->hour < 8;
        $isSunday = $date->isSunday() || $this->isHoliday($date);

        // Majoration ?
        $coefMajoration = 0;
        if ($isNight) $coefMajoration = self::RATES['MAJORATIONS']['NUIT'];
        if ($isSunday) $coefMajoration = max($coefMajoration, self::RATES['MAJORATIONS']['DIMANCHE']); // On prend le max souvent, ou cumul selon convention. Prenons le max pour v1.

        if ($transport->transport_type === 'AMBULANCE') {
            // Logique Ambulance
            // 1. Forfait (Simplifié : Départemental par défaut)
            $forfait = self::RATES['AMBULANCE']['FORFAIT_DEPARTEMENTAL'];
            
            $lines[] = [
                'code' => 'FD',
                'description' => 'Forfait Départemental - Ambulance',
                'quantity' => 1,
                'unit_price' => $forfait,
                'total' => $forfait
            ];
            $total += $forfait;

            // 2. Kilomètres
            // Franchise de X km souvent incluse ? Simplifions : tout km facturé en v1
            $coutKm = $km * self::RATES['AMBULANCE']['KM'];
            if ($km > 0) {
                $lines[] = [
                    'code' => 'KM',
                    'description' => "Indemnité Kilométrique ($km km x " . self::RATES['AMBULANCE']['KM'] . "€)",
                    'quantity' => $km,
                    'unit_price' => self::RATES['AMBULANCE']['KM'],
                    'total' => $coutKm
                ];
                $total += $coutKm;
            }

        } else {
            // Logique VSL / TAXI
             $forfait = self::RATES['VSL']['FORFAIT'];
             
             $lines[] = [
                'code' => 'VSL',
                'description' => 'Forfait VSL',
                'quantity' => 1,
                'unit_price' => $forfait,
                'total' => $forfait
            ];
            $total += $forfait;

            $coutKm = $km * self::RATES['VSL']['KM'];
            if ($km > 0) {
                $lines[] = [
                    'code' => 'KM',
                    'description' => "Indemnité Kilométrique ($km km x " . self::RATES['VSL']['KM'] . "€)",
                    'quantity' => $km,
                    'unit_price' => self::RATES['VSL']['KM'],
                    'total' => $coutKm
                ];
                $total += $coutKm;
            }
        }

        // Application Majoration sur le total partiel (Souvent hors supplément mais simplifions)
        // La majoration s'applique sur le transport (forfait+km)
        if ($coefMajoration > 0) {
            $mantantMajoration = $total * $coefMajoration;
            $typeMaj = $isNight ? 'Nuit' : 'Dimanche/Férié';
            $lines[] = [
                'code' => 'MAJ',
                'description' => "Majoration $typeMaj (+".($coefMajoration*100)."%)",
                'quantity' => 1,
                'unit_price' => $mantantMajoration,
                'total' => $mantantMajoration
            ];
            $total += $mantantMajoration;
        }

        return ['lines' => $lines, 'total' => round($total, 2)];
    }

    // Helper jours fériés France (Fixe + calcul Pâques etc, simplifié ici)
    private function isHoliday(Carbon $date)
    {
        $year = $date->year;
        $holidays = [
            "$year-01-01", "$year-05-01", "$year-05-08", "$year-07-14", "$year-08-15", "$year-11-01", "$year-11-11", "$year-12-25"
        ];
        return in_array($date->format('Y-m-d'), $holidays);
    }

    private function generateInvoiceNumber($companyId)
    {
        $year = now()->year;
        $count = Invoice::where('company_id', $companyId)->whereYear('created_at', $year)->count();
        return 'FAC-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    private function authorizeTransport(Transport $transport)
    {
        $company = Auth::user()->companies()->first();
        if (!$company || $transport->company_id !== $company->id) {
            abort(403);
        }
    }

    private function authorizeInvoice(Invoice $invoice)
    {
        $company = Auth::user()->companies()->first();
        if (!$company || $invoice->company_id !== $company->id) {
            abort(403);
        }
    }
}
