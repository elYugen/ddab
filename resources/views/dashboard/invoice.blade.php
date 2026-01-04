@extends('base')
@section('title', 'Facturation')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1f2937;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-bottom: 24px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-DRAFT { background: #e5e7eb; color: #374151; }
        .status-SENT { background: #dbeafe; color: #1e40af; }
        .status-PAID { background: #d1fae5; color: #047857; }
        .status-CANCELLED { background: #fee2e2; color: #b91c1c; }

        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Position above */
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            pointer-events: none;
        }
        .tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    @include('layout.sidebar')

    <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
        <!-- HEADER -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Facturation</h1>
                <p class="text-gray-500">Gérez vos factures et suivez les paiements</p>
            </div>
            <div>
                <!-- Boutons d'action globaux si nécessaire -->
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('warning'))
        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
        @endif

        <!-- 1. TRANSPORTS EN ATTENTE DE FACTURATION -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Transports à facturer
                </h2>
                <span class="text-sm text-gray-500">{{ count($pendingTransports) }} transports terminés</span>
            </div>

            @if(count($pendingTransports) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Trajet</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pendingTransports as $transport)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($transport->transport_date)->format('d/m/Y') }}
                                <br>
                                <span class="text-xs text-gray-400">{{ substr($transport->start_time, 0, 5) }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $transport->patient->first_name }} {{ $transport->patient->last_name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $transport->transport_type === 'AMBULANCE' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $transport->transport_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="truncate w-48" title="{{ $transport->pickup_address }} -> {{ $transport->destination_address }}">
                                    {{ $transport->destination_address }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $transport->distance_km }} km</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('dashboard.invoice.create-from-transport', $transport->id) }}" 
                                   class="btn-primary text-xs inline-flex items-center gap-1">
                                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-6 6h1m1 0h-5m-9 0h3m2 0h5M9 7h6m-6 3h6m6 1v15m0 0h-5l-2.5-3.5L8 20H3v-15h20v6z"/></svg>
                                   Générer Facture
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                Aucun transport en attente de facturation.
            </div>
            @endif
        </div>

        <!-- 2. HISTORIQUE DES FACTURES -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                Historique des factures
            </h2>
            
            <table id="invoicesTable" class="w-full text-left text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">N° Facture</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Montant</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 rounded-tr-lg text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $invoice->issue_date ? $invoice->issue_date->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">
                            {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}
                            <div class="text-xs text-gray-400">{{ $invoice->patient->address }}</div>
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-900">
                            {{ number_format($invoice->total_amount, 2) }} €
                        </td>
                        <td class="px-4 py-3">
                            <span class="status-badge status-{{ $invoice->status }}">
                                {{ $invoice->status === 'DRAFT' ? 'Brouillon' : 
                                   ($invoice->status === 'SENT' ? 'Envoyée' : 
                                   ($invoice->status === 'PAID' ? 'Payée' : $invoice->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openInvoiceModal('{{ route('dashboard.invoice.show', $invoice->id) }}')" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm p-1 tooltip">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span class="tooltip-text">Voir</span>
                                </button>

                                @if(in_array($invoice->status, ['PAID', 'SENT']))
                                    <span class="text-gray-300 p-1 cursor-not-allowed tooltip">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span class="tooltip-text">Modif. impossible (Envoyée/Payée)</span>
                                    </span>
                                @else
                                    <button onclick="openEditModal({{ $invoice->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-sm p-1 tooltip">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span class="tooltip-text">Éditer</span>
                                    </button>
                                @endif
                                
                                @if($invoice->status !== 'PAID' && $invoice->status !== 'CANCELLED')
                                <form action="{{ route('dashboard.invoice.update', $invoice->id) }}" method="POST" class="inline tooltip">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="PAID">
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm p-1" onclick="return confirm('Confirmer le paiement ?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                    <span class="tooltip-text">Marquer Payée</span>
                                </form>
                                @endif

                                <form action="{{ route('dashboard.invoice.destroy', $invoice->id) }}" method="POST" class="inline tooltip">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm p-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <span class="tooltip-text">Supprimer</span>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- INVOICE MODAL -->
    <div id="invoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeModal()">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div id="modalContent" class="mt-4">
                        <!-- Invoice content will be loaded here -->
                        <div class="flex justify-center py-12">
                            <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a href="#" id="modalPrintBtn" onclick="printModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Imprimer
                    </a>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#invoicesTable').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json" },
            order: [[ 0, "desc" ]] // Order by invoice number desc
        });
    });

    function openInvoiceModal(url) {
        // Show modal
        document.getElementById('invoiceModal').classList.remove('hidden');
        
        // Show loader
        document.getElementById('modalContent').innerHTML = '<div class="flex justify-center py-12"><svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';

        // Fetch content
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('modalContent').innerHTML = '<div class="text-red-500 text-center">Erreur lors du chargement de la facture.</div>';
                console.error('Error:', error);
            });
    }

    function openEditModal(invoiceId) {
        const modal = document.getElementById('invoiceModal');
        const content = document.getElementById('modalContent');
        const body = document.body;

        modal.classList.remove('hidden');
        body.classList.add('overflow-hidden');

        // Reset content with loader
        content.innerHTML = '<div class="flex items-center justify-center p-12"><svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';

        // Load Edit Content
        fetch(`/dashboard/invoice/edit/${invoiceId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            
            // Execute scripts inside the loaded HTML (The partial has embedded scripts for dynamic rows)
            const scripts = content.getElementsByTagName("script");
            for(let i = 0; i < scripts.length; i++) {
                eval(scripts[i].innerText);
            }
        });
    }

    function closeModal() {
        document.getElementById('invoiceModal').classList.add('hidden');
    }

    function printModal() {
        const content = document.getElementById('modalContent').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Imprimer Facture</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
        printWindow.document.write('</head><body >');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endsection
