@extends('base')
@section('title', 'Gestion du stock')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        input:disabled,
        select:disabled,
        textarea:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .modal-backdrop {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: scale(0.9) translateY(-20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-backdrop.active .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            margin-right: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn.view {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-btn.view:hover {
            background: #bfdbfe;
        }

        .action-btn.add {
            background: #d1fae5;
            color: #059669;
        }

        .action-btn.add:hover {
            background: #a7f3d0;
        }

        .action-btn.remove {
            background: #fef3c7;
            color: #d97706;
        }

        .action-btn.remove:hover {
            background: #fde68a;
        }

        .action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: #fecaca;
        }

        .icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .icon-sm {
            width: 16px;
            height: 16px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-top: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon.add {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            color: #4f46e5;
        }

        .modal-icon.view {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .modal-icon.delete {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
        }

        .modal-icon.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .stock-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .stock-badge.ok {
            background: #d1fae5;
            color: #059669;
        }

        .stock-badge.low {
            background: #fef3c7;
            color: #d97706;
        }

        .stock-badge.critical {
            background: #fee2e2;
            color: #dc2626;
        }

        .movement-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .movement-badge.in {
            background: #d1fae5;
            color: #059669;
        }

        .movement-badge.out {
            background: #fee2e2;
            color: #dc2626;
        }

        .movement-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .movement-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .movement-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('content')
    <div class="flex h-screen overflow-hidden">
        @include('layout.sidebar')

        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            <div class="page-header">
                <h1 class="page-title">
                    <svg class="icon" style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Gestion du stock
                </h1>
                <button id="addStockBtn" class="btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvel article
                </button>
            </div>

            <div class="card">
                <table id="stockTable" class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-100">
                        <tr>
                            <th class="p-3 rounded-tl-lg">Article</th>
                            <th class="p-3">Quantité</th>
                            <th class="p-3">Unité</th>
                            <th class="p-3">Seuil d'alerte</th>
                            <th class="p-3">État</th>
                            <th class="p-3 rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL ARTICLE -->
    <div id="stockModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div id="modalIcon" class="modal-icon add">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                </div>
            </div>

            <form id="stockForm" enctype="multipart/form-data">
                <input type="hidden" id="stockId">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nom de l'article</label>
                    <input id="name" placeholder="Gants stériles" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Quantité initiale</label>
                        <input id="quantity" type="number" step="0.01" min="0" placeholder="100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Unité</label>
                        <select id="unit" required>
                            <option value="">Sélectionner...</option>
                            <option value="unité">Unité</option>
                            <option value="boîte">Boîte</option>
                            <option value="litre">Litre</option>
                            <option value="ml">Millilitre</option>
                            <option value="kg">Kilogramme</option>
                            <option value="g">Gramme</option>
                            <option value="rouleau">Rouleau</option>
                            <option value="paire">Paire</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Seuil d'alerte</label>
                    <input id="reorder_threshold" type="number" step="0.01" min="0" placeholder="10">
                    <p class="text-xs text-gray-500 mt-1">Une alerte sera affichée si le stock descend sous ce seuil</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Image (optionnel)</label>
                    <input id="picture" type="file" accept="image/*" class="text-sm">
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" id="closeModal" class="btn-secondary">Fermer</button>
                    <button type="submit" id="submitBtn" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL MOUVEMENT -->
    <div id="movementModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div id="movementIcon" class="modal-icon add">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <h2 id="movementTitle" class="text-xl font-bold text-gray-900">Mouvement de stock</h2>
                </div>
            </div>

            <form id="movementForm">
                <input type="hidden" id="movementStockId">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Type de mouvement</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-green-500"
                            id="typeInLabel">
                            <input type="radio" name="movementType" value="IN" class="hidden" id="typeIn">
                            <span class="font-semibold text-green-600">+ Entrée</span>
                        </label>
                        <label
                            class="flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-red-500"
                            id="typeOutLabel">
                            <input type="radio" name="movementType" value="OUT" class="hidden" id="typeOut">
                            <span class="font-semibold text-red-600">- Sortie</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Quantité</label>
                    <input id="movementQuantity" type="number" step="0.01" min="0.01" placeholder="10" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Motif (optionnel)</label>
                    <input id="movementReason" placeholder="Réapprovisionnement, utilisation...">
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" id="closeMovementModal" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL HISTORIQUE -->
    <div id="historyModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon view">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 id="historyTitle" class="text-xl font-bold text-gray-900">Historique des mouvements</h2>
                </div>
            </div>

            <div id="movementsList" class="movement-list">
                <!-- Mouvements chargés dynamiquement -->
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" id="closeHistoryModal" class="btn-secondary">Fermer</button>
            </div>
        </div>
    </div>

    <!-- MODAL SUPPRESSION -->
    <div id="deleteModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white p-8 rounded-2xl w-full max-w-md shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon delete">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Confirmation</h2>
                </div>
            </div>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cet article ? Tous les mouvements associés
                seront également supprimés.</p>
            <div class="flex justify-end gap-3">
                <button id="cancelDelete" class="btn-secondary">Annuler</button>
                <button id="confirmDelete" class="btn-danger">Supprimer</button>
            </div>
        </div>
    </div>

    <!-- MODAL SUCCÈS -->
    <div id="successModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white p-8 rounded-2xl w-full max-w-sm shadow-2xl text-center">
            <div class="modal-icon success mx-auto mb-4" style="width:64px;height:64px;border-radius:50%">
                <svg class="icon" style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 id="successTitle" class="text-xl font-bold text-gray-900 mb-2">Opération réussie</h2>
            <p id="successMessage" class="text-gray-600 mb-6"></p>
            <button id="closeSuccess" class="btn-primary w-full justify-center">OK</button>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        let table;
        let deleteId = null;

        const icons = {
            view: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            add: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>',
            remove: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>',
            delete: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>'
        };

        function getStockStatus(quantity, threshold) {
            if (quantity <= 0) return '<span class="stock-badge critical">Rupture</span>';
            if (quantity <= threshold) return '<span class="stock-badge low">Stock bas</span>';
            return '<span class="stock-badge ok">OK</span>';
        }

        $(document).ready(function () {
            table = $('#stockTable').DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('dashboard.stock.index') }}",
                    type: "GET",
                },
                order: [[0, 'asc']],
                columns: [
                    { data: 'name' },
                    {
                        data: 'quantity',
                        render: data => parseFloat(data).toLocaleString('fr-FR')
                    },
                    { data: 'unit' },
                    {
                        data: 'reorder_threshold',
                        render: data => parseFloat(data).toLocaleString('fr-FR')
                    },
                    {
                        data: null,
                        render: row => getStockStatus(row.quantity, row.reorder_threshold)
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: id => `
                        <button class="action-btn view historyBtn" data-id="${id}">${icons.view}</button>
                        <button class="action-btn add addStockBtn" data-id="${id}">${icons.add}</button>
                        <button class="action-btn remove removeStockBtn" data-id="${id}">${icons.remove}</button>
                        <button class="action-btn delete deleteBtn" data-id="${id}">${icons.delete}</button>
                    `
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
                }
            });
        });

        // Gestion des radios pour le type de mouvement
        $('input[name="movementType"]').change(function () {
            $('#typeInLabel, #typeOutLabel').removeClass('border-green-500 border-red-500 bg-green-50 bg-red-50');
            if ($(this).val() === 'IN') {
                $('#typeInLabel').addClass('border-green-500 bg-green-50');
            } else {
                $('#typeOutLabel').addClass('border-red-500 bg-red-50');
            }
        });

        // MODAL AJOUT ARTICLE
        $('#addStockBtn').click(() => {
            $('#modalTitle').text('Nouvel article');
            $('#stockForm')[0].reset();
            $('#stockId').val('');
            $('#quantity').prop('disabled', false);
            $('#stockModal').addClass('active');
        });

        $('#closeModal').click(() => $('#stockModal').removeClass('active'));
        $('#closeMovementModal').click(() => $('#movementModal').removeClass('active'));
        $('#closeHistoryModal').click(() => $('#historyModal').removeClass('active'));
        $('#cancelDelete').click(() => $('#deleteModal').removeClass('active'));
        $('#closeSuccess').click(() => $('#successModal').removeClass('active'));

        // CRÉER ARTICLE
        $('#stockForm').submit(function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('quantity', $('#quantity').val());
            formData.append('unit', $('#unit').val());
            formData.append('reorder_threshold', $('#reorder_threshold').val() || 0);
            formData.append('_token', '{{ csrf_token() }}');

            if ($('#picture')[0].files[0]) {
                formData.append('picture', $('#picture')[0].files[0]);
            }

            $.ajax({
                url: '/dashboard/stock/store',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: () => {
                    $('#stockModal').removeClass('active');
                    table.ajax.reload();
                    showSuccess('Article créé', 'L\'article a été ajouté au stock.');
                }
            });
        });

        // MOUVEMENT ENTRÉE
        $(document).on('click', '.addStockBtn', function () {
            $('#movementStockId').val($(this).data('id'));
            $('#movementForm')[0].reset();
            $('#typeIn').prop('checked', true).trigger('change');
            $('#movementTitle').text('Ajouter du stock');
            $('#movementModal').addClass('active');
        });

        // MOUVEMENT SORTIE
        $(document).on('click', '.removeStockBtn', function () {
            $('#movementStockId').val($(this).data('id'));
            $('#movementForm')[0].reset();
            $('#typeOut').prop('checked', true).trigger('change');
            $('#movementTitle').text('Retirer du stock');
            $('#movementModal').addClass('active');
        });

        // SOUMETTRE MOUVEMENT
        $('#movementForm').submit(function (e) {
            e.preventDefault();

            const stockId = $('#movementStockId').val();
            const type = $('input[name="movementType"]:checked').val();

            $.ajax({
                url: `/dashboard/stock/movement/${stockId}`,
                method: 'POST',
                data: {
                    type: type,
                    quantity: $('#movementQuantity').val(),
                    reason: $('#movementReason').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: () => {
                    $('#movementModal').removeClass('active');
                    table.ajax.reload();
                    showSuccess('Mouvement enregistré', type === 'IN' ? 'Le stock a été augmenté.' : 'Le stock a été diminué.');
                },
                error: (xhr) => {
                    alert(xhr.responseJSON?.message || 'Erreur');
                }
            });
        });

        // HISTORIQUE
        $(document).on('click', '.historyBtn', function () {
            const stockId = $(this).data('id');

            $.get(`/dashboard/stock/movements/${stockId}`, function (response) {
                let html = '';
                if (response.data.length === 0) {
                    html = '<p class="text-gray-500 text-center py-8">Aucun mouvement enregistré</p>';
                } else {
                    response.data.forEach(m => {
                        const badge = m.type === 'IN'
                            ? '<span class="movement-badge in">+' + parseFloat(m.quantity).toLocaleString('fr-FR') + '</span>'
                            : '<span class="movement-badge out">-' + parseFloat(m.quantity).toLocaleString('fr-FR') + '</span>';
                        const date = new Date(m.created_at).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                        html += `
                        <div class="movement-item">
                            <div>
                                <div class="font-medium">${m.reason || 'Sans motif'}</div>
                                <div class="text-xs text-gray-500">${date} - ${m.user?.name || 'Utilisateur'}</div>
                            </div>
                            ${badge}
                        </div>
                    `;
                    });
                }
                $('#movementsList').html(html);
                $('#historyModal').addClass('active');
            });
        });

        // SUPPRESSION
        $(document).on('click', '.deleteBtn', function () {
            deleteId = $(this).data('id');
            $('#deleteModal').addClass('active');
        });

        $('#confirmDelete').click(() => {
            $.ajax({
                url: `/dashboard/stock/destroy/${deleteId}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => {
                    $('#deleteModal').removeClass('active');
                    table.ajax.reload();
                }
            });
        });

        function showSuccess(title, message) {
            $('#successTitle').text(title);
            $('#successMessage').text(message);
            $('#successModal').addClass('active');
        }
    </script>
@endsection