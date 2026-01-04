<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->companies()->wherePivot('is_active', true)->first();

        // Fallback si pas d'entreprise active, prendre la première
        if (!$company) {
            $company = $user->companies()->first();
        }

        $stats = [];
        $charts = [];
        $upcomingTransports = [];
        
        if ($company) {
            // --- KPIs ---
            $today = now()->startOfDay();
            $startOfMonth = now()->startOfMonth();

            // Transports
            $todayTransportsCount = $company->transports()->whereDate('transport_date', $today)->count();
            
            // Revenu mensuel (Factures payées ce mois-ci)
            $monthlyRevenue = $company->invoices()
                ->where('status', 'PAID')
                ->where('issue_date', '>=', $startOfMonth)
                ->sum('total_amount');

            // Factures en attente (Envoyées ou Brouillons si on veut tout voir, ou juste Envoyées. "En attente" sous-entend souvent "En attente de paiement" donc SENT. Mais le user dit qu'elles ne s'affichent pas. Probablement car elles sont DRAFT par défaut.)
            // On va inclure SENT et DRAFT pour "En cours"
            $pendingInvoicesCount = $company->invoices()->whereIn('status', ['SENT', 'DRAFT'])->count();
            $pendingInvoicesAmount = $company->invoices()->whereIn('status', ['SENT', 'DRAFT'])->sum('total_amount');

            // Flotte active (Véhicules utilisés aujourd'hui)
            $activeVehiclesCount = $company->transports()
                ->whereDate('transport_date', $today)
                ->distinct('vehicle_id')
                ->count('vehicle_id');
            
            $totalVehicles = $company->vehicles()->count();

            $stats = [
                'today_transports' => $todayTransportsCount,
                'monthly_revenue' => $monthlyRevenue,
                'pending_invoices_count' => $pendingInvoicesCount,
                'pending_invoices_amount' => $pendingInvoicesAmount,
                'active_vehicles' => $activeVehiclesCount,
                'total_vehicles' => $totalVehicles,
                'low_stock' => $company->stockItems()->whereColumn('quantity', '<=', 'reorder_threshold')->count(),
            ];

            // --- CHARTS DATA ---
            
            // 1. Transports sur les 7 derniers jours
            $last7Days = [];
            $transportsPerDay = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $last7Days[] = $date->format('d/m');
                $transportsPerDay[] = $company->transports()
                    ->whereDate('transport_date', $date)
                    ->count();
            }

            // 2. Répartition par type (Mois en cours)
            $transportTypes = $company->transports()
                ->whereMonth('transport_date', now()->month)
                ->selectRaw('transport_type, count(*) as count')
                ->groupBy('transport_type')
                ->pluck('count', 'transport_type')
                ->toArray();

            $charts = [
                'activity_labels' => $last7Days,
                'activity_data' => $transportsPerDay,
                'types_labels' => array_keys($transportTypes),
                'types_data' => array_values($transportTypes),
            ];

            // --- UPCOMING TRANSPORTS (Next 5) ---
            $upcomingTransports = $company->transports()
                ->with(['patient', 'vehicle', 'driver'])
                ->where('transport_date', '>=', $today)
                ->orderBy('transport_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact('user', 'company', 'stats', 'charts', 'upcomingTransports'));
    }

    public function patient()
    {
        return view('dashboard.patient');
    }

    public function vehicle()
    {
        return view('dashboard.vehicle');
    }

    public function user()
    {
        return view('dashboard.user');
    }

    public function disinfection()
    {
        return view('dashboard.disinfection');
    }

    public function documentation()
    {
        return view('dashboard.documentation');
    }

    public function stock()
    {
        return view('dashboard.stock');
    }

    public function myDocuments()
    {
        return view('dashboard.my-documents');
    }

    public function documents()
    {
        // Vérifier que l'utilisateur est admin ou owner
        $company = Auth::user()->companies()->first();
        if ($company) {
            $role = strtolower($company->pivot->role);
            if (!in_array($role, ['admin', 'owner'])) {
                abort(403, 'Accès réservé aux administrateurs');
            }
        }
        return view('dashboard.documents');
    }

    public function transport()
    {
        return view('dashboard.transport');
    }

}
