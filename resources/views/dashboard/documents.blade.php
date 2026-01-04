@extends('base')
@section('title', 'Documents entreprise')

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
        select:disabled {
            background: #f3f4f6;
            color: #6b7280;
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

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
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

        .action-btn.download {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-btn.download:hover {
            background: #bfdbfe;
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

        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }

        .tab {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .tab.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }

        .tab:not(.active) {
            background: #f3f4f6;
            color: #4b5563;
        }

        .tab:not(.active):hover {
            background: #e5e7eb;
        }

        .modal-header {
            display: flex;
            align-items: center;
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

        .modal-icon.delete {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
        }

        .modal-icon.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .doc-type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .doc-type-badge.agrement {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .doc-type-badge.assurance {
            background: #d1fae5;
            color: #059669;
        }

        .doc-type-badge.afgsu {
            background: #fef3c7;
            color: #d97706;
        }

        .doc-type-badge.diplome {
            background: #ede9fe;
            color: #7c3aed;
        }

        .doc-type-badge.license {
            background: #fce7f3;
            color: #db2777;
        }

        .doc-type-badge.other {
            background: #f3f4f6;
            color: #6b7280;
        }

        .source-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .source-badge.company {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .source-badge.user {
            background: #d1fae5;
            color: #059669;
        }

        .file-upload {
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: #6366f1;
            background: #f5f3ff;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Ensure DataTables take full width */
        #companyDocsTable_wrapper,
        #userDocsTable_wrapper {
            width: 100%;
        }

        #companyDocsTable,
        #userDocsTable {
            width: 100% !important;
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
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Documents entreprise
                </h1>
                <button id="addDocBtn" class="btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter un document
                </button>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" data-tab="company">Documents entreprise</div>
                <div class="tab" data-tab="users">Documents salariés</div>
            </div>

            <!-- Tab: Documents entreprise -->
            <div id="tab-company" class="tab-content active">
                <div class="card" style="margin-top: 0;">
                    <table id="companyDocsTable" class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100">
                            <tr>
                                <th class="p-3 rounded-tl-lg">Nom</th>
                                <th class="p-3">Type</th>
                                <th class="p-3">Notes</th>
                                <th class="p-3">Date</th>
                                <th class="p-3 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Tab: Documents salariés -->
            <div id="tab-users" class="tab-content">
                <div class="card" style="margin-top: 0;">
                    <table id="userDocsTable" class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100">
                            <tr>
                                <th class="p-3 rounded-tl-lg">Salarié</th>
                                <th class="p-3">Document</th>
                                <th class="p-3">Type</th>
                                <th class="p-3">Date</th>
                                <th class="p-3 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL AJOUT DOCUMENT ENTREPRISE -->
    <div id="docModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon add">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Document entreprise</h2>
                </div>
            </div>

            <form id="docForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nom du document</label>
                    <input id="docName" placeholder="Agrément ARS 2024" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                        <select id="docType" required>
                            <option value="">Sélectionner...</option>
                            <option value="AGREMENT">Agrément</option>
                            <option value="ASSURANCE">Assurance</option>
                            <option value="OTHER">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Notes</label>
                        <input id="docNotes" placeholder="Notes optionnelles">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Fichier</label>
                    <div class="file-upload" id="fileUploadZone">
                        <svg class="icon mx-auto mb-2 text-gray-400" style="width:48px;height:48px" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-gray-600 font-medium">Cliquez ou glissez un fichier</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 10 Mo)</p>
                        <input type="file" id="docFile" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <p id="fileName" class="text-sm text-indigo-600 mt-2 hidden"></p>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" id="closeModal" class="btn-secondary">Fermer</button>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
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
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer ce document ?</p>
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
            <h2 class="text-xl font-bold text-gray-900 mb-2">Document ajouté</h2>
            <p class="text-gray-600 mb-6">Le document a été enregistré avec succès.</p>
            <button id="closeSuccess" class="btn-primary w-full justify-center">OK</button>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        let companyTable, userTable;
        let deleteId = null;

        const companyTypeLabels = { 'AGREMENT': 'agrement', 'ASSURANCE': 'assurance', 'OTHER': 'other' };
        const userTypeLabels = { 'AFGSU': 'afgsu', 'DIPLOME': 'diplome', 'LICENSE': 'license', 'OTHER': 'other' };

        const icons = {
            download: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
            delete: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>'
        };

        $(document).ready(function () {
            // Table documents entreprise
            companyTable = $('#companyDocsTable').DataTable({
                processing: true,
                ajax: { url: "{{ route('dashboard.documents.company') }}", type: "GET" },
                order: [[3, 'desc']],
                columns: [
                    { data: 'name' },
                    { data: 'document_type', render: type => `<span class="doc-type-badge ${companyTypeLabels[type]}">${type}</span>` },
                    { data: 'notes', render: data => data || '-' },
                    { data: 'created_at', render: data => new Date(data).toLocaleDateString('fr-FR') },
                    {
                        data: 'id',
                        orderable: false,
                        render: id => `
                        <a href="/dashboard/documents/company/download/${id}" class="action-btn download">${icons.download}</a>
                        <button class="action-btn delete deleteCompanyBtn" data-id="${id}">${icons.delete}</button>
                    `
                    }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json" }
            });

            // Table documents salariés
            userTable = $('#userDocsTable').DataTable({
                processing: true,
                ajax: { url: "{{ route('dashboard.documents.users') }}", type: "GET" },
                order: [[3, 'desc']],
                columns: [
                    { data: 'user', render: user => user ? `${user.first_name} ${user.last_name}` : 'N/A' },
                    { data: 'name' },
                    { data: 'document_type', render: type => `<span class="doc-type-badge ${userTypeLabels[type]}">${type}</span>` },
                    { data: 'created_at', render: data => new Date(data).toLocaleDateString('fr-FR') },
                    {
                        data: 'id',
                        orderable: false,
                        render: id => `<a href="/dashboard/documents/user/download/${id}" class="action-btn download">${icons.download}</a>`
                    }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json" }
            });
        });

        // Tabs
        $('.tab').click(function () {
            const tabId = $(this).data('tab');
            $('.tab').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').removeClass('active');
            $(`#tab-${tabId}`).addClass('active');
        });

        // File upload
        const fileUploadZone = document.getElementById('fileUploadZone');
        const fileInput = document.getElementById('docFile');
        const fileName = document.getElementById('fileName');

        fileUploadZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                fileName.textContent = fileInput.files[0].name;
                fileName.classList.remove('hidden');
            }
        });

        $('#addDocBtn').click(() => {
            $('#docForm')[0].reset();
            fileName.classList.add('hidden');
            $('#docModal').addClass('active');
        });

        $('#closeModal').click(() => $('#docModal').removeClass('active'));
        $('#cancelDelete').click(() => $('#deleteModal').removeClass('active'));
        $('#closeSuccess').click(() => $('#successModal').removeClass('active'));

        $('#docForm').submit(function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('name', $('#docName').val());
            formData.append('document_type', $('#docType').val());
            formData.append('notes', $('#docNotes').val());
            formData.append('file', $('#docFile')[0].files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '/dashboard/documents/company/store',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: () => {
                    $('#docModal').removeClass('active');
                    companyTable.ajax.reload();
                    $('#successModal').addClass('active');
                },
                error: (xhr) => alert(xhr.responseJSON?.message || 'Erreur')
            });
        });

        $(document).on('click', '.deleteCompanyBtn', function () {
            deleteId = $(this).data('id');
            $('#deleteModal').addClass('active');
        });

        $('#confirmDelete').click(() => {
            $.ajax({
                url: `/dashboard/documents/company/destroy/${deleteId}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => {
                    $('#deleteModal').removeClass('active');
                    companyTable.ajax.reload();
                }
            });
        });
    </script>
@endsection