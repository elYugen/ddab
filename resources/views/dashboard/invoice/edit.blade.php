@extends('base')
@section('title', 'Modifier Facture #' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Modifier Facture #{{ $invoice->invoice_number }}</h1>
        <a href="{{ route('dashboard.invoice.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Retour aux factures</a>
    </div>

    <form action="{{ route('dashboard.invoice.update', $invoice->id) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf
        @method('PUT')

        <!-- Infos Générales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'émission</label>
                <input type="date" name="issue_date" value="{{ $invoice->issue_date->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
                <input type="date" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="DRAFT" {{ $invoice->status == 'DRAFT' ? 'selected' : '' }}>Brouillon</option>
                    <option value="SENT" {{ $invoice->status == 'SENT' ? 'selected' : '' }}>Envoyée</option>
                    <option value="PAID" {{ $invoice->status == 'PAID' ? 'selected' : '' }}>Payée</option>
                    <option value="CANCELLED" {{ $invoice->status == 'CANCELLED' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
        </div>

        <!-- Lignes de facture -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Lignes de facturation</h2>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left" id="itemsTable">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase text-gray-500">
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 w-24 text-right">Qté</th>
                        <th class="px-4 py-3 w-32 text-right">Prix Unit.</th>
                        <th class="px-4 py-3 w-32 text-right">Total</th>
                        <th class="px-4 py-3 w-16 text-center"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="itemsContainer">
                    @foreach($invoice->items as $index => $item)
                    <tr class="item-row">
                        <td class="p-2">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" class="w-full rounded border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </td>
                        <td class="p-2">
                            <input type="number" step="0.01" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="w-full rounded border-gray-300 text-sm text-right qty-input focus:ring-indigo-500 focus:border-indigo-500" required oninput="calcRow(this)">
                        </td>
                        <td class="p-2">
                            <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" class="w-full rounded border-gray-300 text-sm text-right price-input focus:ring-indigo-500 focus:border-indigo-500" required oninput="calcRow(this)">
                        </td>
                        <td class="p-2 text-right font-bold text-gray-700 row-total">
                            {{ number_format($item->total, 2) }} €
                        </td>
                        <td class="p-2 text-center">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="button" onclick="addItem()" class="mb-8 text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter une ligne
        </button>

        <!-- Totals Preview -->
        <div class="flex justify-end border-t border-gray-100 pt-6">
             <div class="w-64 text-right">
                <div class="text-sm text-gray-500 mb-1">Total estimé</div>
                <div class="text-2xl font-bold text-gray-900" id="grandTotal">{{ number_format($invoice->total_amount, 2) }} €</div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
             <a href="{{ route('dashboard.invoice.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<script>
    let itemIndex = {{ $invoice->items->count() }};

    function addItem() {
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
            <td class="p-2">
                <input type="text" name="items[${itemIndex}][description]" placeholder="Description" class="w-full rounded border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </td>
            <td class="p-2">
                <input type="number" step="0.01" name="items[${itemIndex}][quantity]" value="1" class="w-full rounded border-gray-300 text-sm text-right qty-input focus:ring-indigo-500 focus:border-indigo-500" required oninput="calcRow(this)">
            </td>
            <td class="p-2">
                <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" value="0.00" class="w-full rounded border-gray-300 text-sm text-right price-input focus:ring-indigo-500 focus:border-indigo-500" required oninput="calcRow(this)">
            </td>
            <td class="p-2 text-right font-bold text-gray-700 row-total">0.00 €</td>
            <td class="p-2 text-center">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </td>
        `;
        container.appendChild(row);
        itemIndex++;
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calcTotal();
    }

    function calcRow(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.row-total').textContent = total.toFixed(2) + ' €';
        calcTotal();
    }

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            total += qty * price;
        });
        document.getElementById('grandTotal').textContent = total.toFixed(2) + ' €';
    }
</script>
@endsection
