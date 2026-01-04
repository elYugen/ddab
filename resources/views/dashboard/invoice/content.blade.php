<div class="bg-white shadow-lg p-12 rounded-xl print:shadow-none print:p-0 print:rounded-none" id="invoice-content">
    <!-- Header -->
    <div class="flex justify-between items-start mb-12">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">FACTURE</h1>
            <p class="text-gray-500 font-medium">#{{ $invoice->invoice_number }}</p>
            <div class="mt-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($invoice->status === 'PAID') bg-green-100 text-green-800
                    @elseif($invoice->status === 'SENT') bg-blue-100 text-blue-800
                    @elseif($invoice->status === 'CANCELLED') bg-gray-100 text-gray-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    @if($invoice->status === 'PAID') PAYÉE
                    @elseif($invoice->status === 'DRAFT') BROUILLON
                    @elseif($invoice->status === 'SENT') ENVOYÉE
                    @elseif($invoice->status === 'CANCELLED') ANNULÉE
                    @else {{ $invoice->status }} @endif
                </span>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-800">{{ $company->name }}</h2>
            <p class="text-gray-600 text-sm mt-1">
                {{ $company->address ?? 'Adresse non renseignée' }}<br>
                {{ $company->zip_code ?? '' }} {{ $company->city ?? '' }}<br>
                SIRET: {{ $company->siret ?? 'N/A' }}<br>
                Tel: {{ $company->phone ?? 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Client & Info -->
    <div class="flex justify-between mb-12">
        <div>
            <h3 class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-2">FACTURÉ À</h3>
            <div class="text-gray-900 font-medium">
                {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}
            </div>
            <div class="text-gray-600 text-sm mt-1">
                {{ $invoice->patient->address }}<br>
                {{ $invoice->patient->zip_code }} {{ $invoice->patient->city }}<br>
                Sécu: {{ $invoice->patient->social_security_number ?? 'N/A' }}
            </div>
        </div>
        <div class="text-right">
            <div class="mb-2">
                <span class="text-gray-500 uppercase text-xs font-semibold tracking-wider">DATE D'ÉMISSION</span>
                <div class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</div>
            </div>
            <div>
                <span class="text-gray-500 uppercase text-xs font-semibold tracking-wider">DATE D'ÉCHÉANCE</span>
                <div class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Transport Details (Optional context) -->
    <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-100 text-sm">
        <h4 class="font-semibold text-gray-700 mb-2">Détails du transport</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-500">Date :</span> 
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($invoice->transport->transport_date)->format('d/m/Y') }} à {{ substr($invoice->transport->start_time, 0, 5) }}</span>
            </div>
            <div>
                <span class="text-gray-500">Trajet :</span> 
                <span class="text-gray-900">{{ $invoice->transport->departure_address }} → {{ $invoice->transport->destination_address }}</span>
            </div>
            <div>
                <span class="text-gray-500">Prescription :</span> 
                <span class="text-gray-900">{{ $invoice->transport->prescription_type ?? 'Non spécifié' }}</span>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="w-full mb-8">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left py-3 text-gray-500 uppercase text-xs font-semibold tracking-wider">Description</th>
                <th class="text-right py-3 text-gray-500 uppercase text-xs font-semibold tracking-wider w-24">Qté</th>
                <th class="text-right py-3 text-gray-500 uppercase text-xs font-semibold tracking-wider w-32">Prix Unit.</th>
                <th class="text-right py-3 text-gray-500 uppercase text-xs font-semibold tracking-wider w-32">Total</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
            @foreach($invoice->items as $item)
            <tr class="border-b border-gray-100 last:border-0">
                <td class="py-4">
                    <div class="font-medium text-gray-900">{{ $item->description }}</div>
                    @if($item->code)
                        <div class="text-xs text-gray-400">Code: {{ $item->code }}</div>
                    @endif
                </td>
                <td class="text-right py-4">{{ $item->quantity }}</td>
                <td class="text-right py-4">{{ number_format($item->unit_price, 2) }} €</td>
                <td class="text-right py-4 font-medium text-gray-900">{{ number_format($item->total, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="flex justify-end border-t border-gray-200 pt-8">
        <div class="w-64">
            <div class="flex justify-between mb-2 text-gray-600">
                <span>Sous-total HT</span>
                <span>{{ number_format($invoice->subtotal, 2) }} €</span>
            </div>
            <div class="flex justify-between mb-2 text-gray-600">
                <span>TVA ({{ $invoice->tax_amount > 0 ? '10%' : '0%' }})</span>
                <span>{{ number_format($invoice->tax_amount, 2) }} €</span>
            </div>
            <div class="flex justify-between font-bold text-xl text-gray-900 mt-4 pt-4 border-t border-gray-200">
                <span>Total TTC</span>
                <span>{{ number_format($invoice->total_amount, 2) }} €</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-12 pt-8 border-t border-gray-100 text-center text-gray-500 text-xs">
        <p>Facture générée par {{ config('app.name') }}</p>
        <p class="mt-1">Conditions de paiement : {{ $company->payment_terms ?? '30 jours' }}</p>
    </div>
</div>
