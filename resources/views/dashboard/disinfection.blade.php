@extends('base')
@section('title', 'Gestion des désinfections')

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

        input::placeholder,
        select::placeholder,
        textarea::placeholder {
            color: #9ca3af;
        }

        input:disabled,
        select:disabled,
        textarea:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
            border-color: #e5e7eb;
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

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
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
            transform: translateY(-1px);
        }

        .action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: #fecaca;
            transform: translateY(-1px);
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
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

        #disinfectionsTable_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        #disinfectionsTable_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

        .view-mode input,
        .view-mode select,
        .view-mode textarea {
            background: #f9fafb;
            border-color: transparent;
            color: #374151;
        }

        .type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-badge.daily {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .type-badge.weekly {
            background: #fef3c7;
            color: #d97706;
        }

        .type-badge.deep {
            background: #d1fae5;
            color: #059669;
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
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Gestion des désinfections
                </h1>
                <button id="addDisinfectionBtn" class="btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle désinfection
                </button>
            </div>

            <div class="card">
                <table id="disinfectionsTable" class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-100">
                        <tr>
                            <th class="p-3 rounded-tl-lg">Véhicule</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Protocole</th>
                            <th class="p-3">Produit</th>
                            <th class="p-3">Effectuée par</th>
                            <th class="p-3 rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DÉSINFECTION (Ajout / Voir / Éditer) -->
    <div id="disinfectionModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div id="modalIcon" class="modal-icon add">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                </div>
                <button type="button" id="enableEditBtn" class="btn-edit hidden">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Modifier
                </button>
            </div>

            <form id="disinfectionForm">
                <input type="hidden" id="disinfectionId">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Véhicule</label>
                        <select id="vehicle_id" required>
                            <option value="">Sélectionner un véhicule...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Date de désinfection</label>
                        <input id="disinfected_at" type="date" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Type de désinfection</label>
                        <select id="type" required>
                            <option value="">Sélectionner...</option>
                            <option value="daily">Quotidienne</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="deep">En profondeur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Référence protocole</label>
                        <input id="protocol_reference" placeholder="PROT-2024-001" required>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Produit utilisé</label>
                    <input id="product_used" placeholder="Nom du produit désinfectant" required>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Remarques</label>
                    <textarea id="remarks" rows="3" placeholder="Observations, notes complémentaires..."></textarea>
                </div>

                <div id="modalFooter" class="flex justify-end gap-3 mt-8">
                    <button type="button" id="closeModal" class="btn-secondary">
                        <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Fermer
                    </button>
                    <button type="submit" id="submitBtn" class="btn-primary">
                        <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal suppression -->
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
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette désinfection ? Cette action est
                irréversible.</p>

            <div class="flex justify-end gap-3">
                <button id="cancelDelete" class="btn-secondary">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Annuler
                </button>
                <button id="confirmDelete" class="btn-danger">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Modal succès -->
    <div id="successModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white p-8 rounded-2xl w-full max-w-sm shadow-2xl text-center">
            <div class="modal-icon success mx-auto mb-4" style="width:64px;height:64px;border-radius:50%">
                <svg class="icon" style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 id="successTitle" class="text-xl font-bold text-gray-900 mb-2">Modification enregistrée</h2>
            <p id="successMessage" class="text-gray-600 mb-6">Les modifications ont bien été prises en compte.</p>
            <button id="closeSuccess" class="btn-primary w-full justify-center">
                <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                OK
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        let table;
        let isViewMode = false;
        let vehiclesCache = [];

        // Labels pour les types
        const typeLabels = {
            'daily': 'Quotidienne',
            'weekly': 'Hebdomadaire',
            'deep': 'En profondeur'
        };

        // icone pour les boutons d'action
        const icons = {
            view: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>',
            delete: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>'
        };

        // Charger les véhicules pour le select
        function loadVehicles() {
            $.get("{{ route('dashboard.disinfection.vehicles') }}", function (response) {
                vehiclesCache = response.data;
                let options = '<option value="">Sélectionner un véhicule...</option>';
                response.data.forEach(v => {
                    options += `<option value="${v.id}">${v.name} (${v.registration_number})</option>`;
                });
                $('#vehicle_id').html(options);
            });
        }

        $(document).ready(function () {
            // Charger les véhicules au démarrage
            loadVehicles();

            table = $('#disinfectionsTable').DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('dashboard.disinfection.index') }}",
                    type: "GET",
                },
                order: [[1, 'desc']],
                columns: [
                    {
                        data: 'vehicle',
                        render: data => data ? `${data.name} (${data.registration_number})` : 'N/A'
                    },
                    {
                        data: 'disinfected_at',
                        render: data => data ? new Date(data).toLocaleDateString('fr-FR') : ''
                    },
                    {
                        data: 'type',
                        render: data => `<span class="type-badge ${data}">${typeLabels[data] || data}</span>`
                    },
                    { data: 'protocol_reference' },
                    { data: 'product_used' },
                    {
                        data: 'user',
                        render: data => data ? data.name : 'N/A'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `
                            <button class="action-btn view viewBtn" data-id="${data}">${icons.view} Voir</button>
                            <button class="action-btn delete deleteBtn" data-id="${data}">${icons.delete}</button>
                        `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
                }
            });
        });

        // Fonction pour activer/désactiver les inputs
        function setInputsDisabled(disabled) {
            $('#disinfectionForm input:not([type="hidden"]), #disinfectionForm select, #disinfectionForm textarea').prop('disabled', disabled);
            isViewMode = disabled;
        }

        // Fonction pour mettre à jour l'UI selon le mode
        function updateModalUI(mode) {
            if (mode === 'view') {
                setInputsDisabled(true);
                $('#enableEditBtn').removeClass('hidden');
                $('#submitBtn').addClass('hidden');
                $('#modalIcon').removeClass('add').addClass('view');
                $('#disinfectionForm').addClass('view-mode');
            } else if (mode === 'edit') {
                setInputsDisabled(false);
                $('#enableEditBtn').addClass('hidden');
                $('#submitBtn').removeClass('hidden');
                $('#modalIcon').removeClass('view').addClass('add');
                $('#disinfectionForm').removeClass('view-mode');
            } else { // add
                setInputsDisabled(false);
                $('#enableEditBtn').addClass('hidden');
                $('#submitBtn').removeClass('hidden');
                $('#modalIcon').removeClass('view').addClass('add');
                $('#disinfectionForm').removeClass('view-mode');
            }
        }
    </script>
    <script>
        let deleteId = null;

        /* OUVERTURE MODAL AJOUT */
        $('#addDisinfectionBtn').click(() => {
            $('#modalTitle').text('Nouvelle désinfection');
            $('#disinfectionForm')[0].reset();
            $('#disinfectionId').val('');
            // Mettre la date du jour par défaut
            $('#disinfected_at').val(new Date().toISOString().split('T')[0]);
            updateModalUI('add');
            $('#disinfectionModal').addClass('active');
        });

        /* BOUTON ACTIVER ÉDITION */
        $('#enableEditBtn').click(() => {
            $('#modalTitle').text('Modifier la désinfection');
            updateModalUI('edit');
        });

        /* FERMETURE MODALES */
        $('#closeModal').click(() => $('#disinfectionModal').removeClass('active'));
        $('#cancelDelete').click(() => $('#deleteModal').removeClass('active'));
        $('#closeSuccess').click(() => $('#successModal').removeClass('active'));

        /* CRÉATION / ÉDITION */
        $('#disinfectionForm').submit(function (e) {
            e.preventDefault();

            // Ne pas soumettre en mode lecture
            if (isViewMode) return;

            const id = $('#disinfectionId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id
                ? `/dashboard/disinfection/update/${id}`
                : `/dashboard/disinfection/store`;

            $.ajax({
                url,
                method,
                data: {
                    vehicle_id: $('#vehicle_id').val(),
                    disinfected_at: $('#disinfected_at').val(),
                    type: $('#type').val(),
                    protocol_reference: $('#protocol_reference').val(),
                    product_used: $('#product_used').val(),
                    remarks: $('#remarks').val(),
                    _token: "{{ csrf_token() }}"
                },
                success: () => {
                    $('#disinfectionModal').removeClass('active');
                    table.ajax.reload();

                    // Afficher la modal de succès
                    if (id) {
                        $('#successTitle').text('Modification enregistrée');
                        $('#successMessage').text('Les modifications ont bien été prises en compte.');
                    } else {
                        $('#successTitle').text('Désinfection enregistrée');
                        $('#successMessage').text('La nouvelle désinfection a été créée avec succès.');
                    }
                    $('#successModal').addClass('active');
                }
            });
        });

        /* VOIR LA DÉSINFECTION */
        $(document).on('click', '.viewBtn', function () {
            const id = $(this).data('id');

            $.get(`/dashboard/disinfection/get/${id}`, function (disinfection) {
                $('#modalTitle').text('Fiche désinfection');
                $('#disinfectionId').val(disinfection.id);
                $('#vehicle_id').val(disinfection.vehicle_id);
                $('#type').val(disinfection.type);
                $('#protocol_reference').val(disinfection.protocol_reference);
                $('#product_used').val(disinfection.product_used);
                $('#remarks').val(disinfection.remarks);

                // Formater la date au format YYYY-MM-DD pour l'input date
                if (disinfection.disinfected_at) {
                    $('#disinfected_at').val(new Date(disinfection.disinfected_at).toISOString().split('T')[0]);
                } else {
                    $('#disinfected_at').val('');
                }

                updateModalUI('view');
                $('#disinfectionModal').addClass('active');
            });
        });

        /* SUPPRESSION */
        $(document).on('click', '.deleteBtn', function () {
            deleteId = $(this).data('id');
            $('#deleteModal').addClass('active');
        });

        $('#confirmDelete').click(() => {
            $.ajax({
                url: `/dashboard/disinfection/destroy/${deleteId}`,
                method: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: () => {
                    $('#deleteModal').removeClass('active');
                    table.ajax.reload();
                }
            });
        });
    </script>

@endsection