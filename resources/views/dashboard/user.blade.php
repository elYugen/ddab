@extends('base')
@section('title', 'Gestion des utilisateurs')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
input, select, textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f9fafb;
}
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}
input::placeholder, select::placeholder, textarea::placeholder {
    color: #9ca3af;
}
input:disabled, select:disabled, textarea:disabled {
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

#usersTable_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}
#usersTable_wrapper .dataTables_filter input:focus {
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
</style>
@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    @include('layout.sidebar')

    <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
        <div class="page-header">
            <h1 class="page-title">
                <svg class="icon" style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Gestion des utilisateurs
            </h1>
            <button id="addUserBtn" class="btn-primary">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un utilisateur
            </button>
        </div>

        <div class="card">
            <table id="usersTable" class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100">
                    <tr>
                        <th class="p-3 rounded-tl-lg">Nom</th>
                        <th class="p-3">Prénom</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Téléphone</th>
                        <th class="p-3">Rôle</th>
                        <th class="p-3">Statut</th>
                        <th class="p-3 rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL UTILISATEUR (Ajout / Voir / Éditer) -->
<div id="userModal" class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
        <div class="modal-header">
            <div class="modal-header-left">
                <div id="modalIcon" class="modal-icon add">
                    <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
            </div>
            <button type="button" id="enableEditBtn" class="btn-edit hidden">
                <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Modifier
            </button>
        </div>

        <form id="userForm">
            <input type="hidden" id="userId">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Prénom</label>
                    <input id="first_name" placeholder="Prénom" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nom</label>
                    <input id="last_name" placeholder="Nom" required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                <input id="email" type="email" placeholder="email@exemple.com" required>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-600 mb-2">Téléphone</label>
                <input id="phone" placeholder="06 00 00 00 00">
            </div>

            <div id="passwordContainer" class="mt-4">
                <label class="block text-sm font-medium text-gray-600 mb-2">Mot de passe</label>
                <input id="password" type="password" placeholder="********">
                <p id="passwordHelp" class="text-xs text-gray-500 mt-1 hidden">Laissez vide pour ne pas modifier.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Rôle</label>
                    <select id="role" required>
                        <option value="">Sélectionner...</option>
                        <option value="admin">Administrateur</option>
                        <option value="employee">Salarié</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Statut</label>
                    <div class="flex items-center h-full">
                        <input id="is_active" type="checkbox" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" checked>
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Compte actif</label>
                    </div>
                </div>
            </div>

            <div id="modalFooter" class="flex justify-end gap-3 mt-8">
                <button type="button" id="closeModal" class="btn-secondary">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Fermer
                </button>
                <button type="submit" id="submitBtn" class="btn-primary">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal suppression -->
<div id="deleteModal" class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon delete">
                    <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Confirmation</h2>
            </div>
        </div>
        <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action désactivera son accès.</p>

        <div class="flex justify-end gap-3">
            <button id="cancelDelete" class="btn-secondary">
                <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Annuler
            </button>
            <button id="confirmDelete" class="btn-danger">
                <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Modal succès -->
<div id="successModal" class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-2xl w-full max-w-sm shadow-2xl text-center">
        <div class="modal-icon success mx-auto mb-4" style="width:64px;height:64px;border-radius:50%">
            <svg class="icon" style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 id="successTitle" class="text-xl font-bold text-gray-900 mb-2">Modification enregistrée</h2>
        <p id="successMessage" class="text-gray-600 mb-6">Les modifications ont bien été prises en compte.</p>
        <button id="closeSuccess" class="btn-primary w-full justify-center">
            <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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

// icone pour les boutons d'action
const icons = {
    view: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>',
    delete: '<svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>'
};

$(document).ready(function () {
    table = $('#usersTable').DataTable({
        processing: true,
        ajax: {
            url: "{{ route('dashboard.user.index') }}",
            type: "GET",
        },
        order: [],
        columns: [
            { data: 'last_name' },
            { data: 'first_name' },
            { data: 'email' },
            { data: 'phone', render: data => data || 'N/A' },
            { 
               data: 'pivot.role', 
               render: data => {
                    const roles = {
                        'owner': '<span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Propriétaire</span>',
                        'admin': '<span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Admin</span>',
                        'employee': '<span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Salarié</span>'
                    };
                    return roles[data] || data;
               }
            },
            { 
                data: 'pivot.is_active',
                render: data => data ? '<span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Actif</span>' : '<span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Inactif</span>'
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
    $('#userForm input:not([type="hidden"]):not([type="checkbox"]), #userForm select').prop('disabled', disabled);
    $('#userForm input[type="checkbox"]').prop('disabled', disabled);
    isViewMode = disabled;
}

// Fonction pour mettre à jour l'UI selon le mode
function updateModalUI(mode) {
    if (mode === 'view') {
        setInputsDisabled(true);
        $('#enableEditBtn').removeClass('hidden');
        $('#submitBtn').addClass('hidden');
        $('#modalIcon').removeClass('add').addClass('view');
        $('#userForm').addClass('view-mode');
        $('#passwordContainer').addClass('hidden');
        $('#role').parent().removeClass('hidden');
        $('#role').prop('required', false);
    } else if (mode === 'edit') {
        setInputsDisabled(false);
        $('#enableEditBtn').addClass('hidden');
        $('#submitBtn').removeClass('hidden');
        $('#modalIcon').removeClass('view').addClass('add');
        $('#userForm').removeClass('view-mode');
        $('#passwordContainer').removeClass('hidden');
        $('#passwordHelp').removeClass('hidden');
        $('#password').prop('required', false);
        $('#role').parent().addClass('hidden');
        $('#role').prop('required', false);
    } else { // add
        setInputsDisabled(false);
        $('#enableEditBtn').addClass('hidden');
        $('#submitBtn').removeClass('hidden');
        $('#modalIcon').removeClass('view').addClass('add');
        $('#userForm').removeClass('view-mode');
        $('#passwordContainer').removeClass('hidden');
        $('#passwordHelp').addClass('hidden');
        $('#password').prop('required', true);
        $('#role').parent().removeClass('hidden');
        $('#role').prop('required', true);
    }
}
</script>
<script>
let deleteId = null;

/* OUVERTURE MODAL AJOUT */
$('#addUserBtn').click(() => {
    $('#modalTitle').text('Nouvel utilisateur');
    $('#userForm')[0].reset();
    $('#userId').val('');
    updateModalUI('add');
    $('#userModal').addClass('active');
});

/* BOUTON ACTIVER ÉDITION */
$('#enableEditBtn').click(() => {
    $('#modalTitle').text('Modifier l\'utilisateur');
    updateModalUI('edit');
});

/* FERMETURE MODALES */
$('#closeModal').click(() => $('#userModal').removeClass('active'));
$('#cancelDelete').click(() => $('#deleteModal').removeClass('active'));
$('#closeSuccess').click(() => $('#successModal').removeClass('active'));

/* CRÉATION / ÉDITION */
$('#userForm').submit(function (e) {
    e.preventDefault();

    // Ne pas soumettre en mode lecture
    if (isViewMode) return;

    const id = $('#userId').val();
    const method = id ? 'PUT' : 'POST';
    const url = id
        ? `/dashboard/user/update/${id}`
        : `/dashboard/user/store`;

    const data = {
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val(),
        email: $('#email').val(),
        phone: $('#phone').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0,
        _token: "{{ csrf_token() }}"
    };

    if ($('#password').val()) {
        data.password = $('#password').val();
    }

    if (!id) {
        data.role = $('#role').val();
    }

    $.ajax({
        url,
        method,
        data,
        success: () => {
            $('#userModal').removeClass('active');
            table.ajax.reload();
            
            // Afficher la modal de succès
            if (id) {
                $('#successTitle').text('Modification enregistrée');
                $('#successMessage').text('Les modifications ont bien été prises en compte.');
            } else {
                $('#successTitle').text('Utilisateur ajouté');
                $('#successMessage').text('Le nouvel utilisateur a été créé avec succès.');
            }
            $('#successModal').addClass('active');
        },
        error: (xhr) => {
            alert(xhr.responseJSON?.message || 'Une erreur est survenue');
        }
    });
});

/* VOIR L'UTILISATEUR */
$(document).on('click', '.viewBtn', function () {
    const id = $(this).data('id');

    $.get(`/dashboard/user/get/${id}`, function (user) {
        $('#modalTitle').text('Fiche utilisateur');
        $('#userId').val(user.id);
        $('#first_name').val(user.first_name);
        $('#last_name').val(user.last_name);
        $('#email').val(user.email);
        $('#phone').val(user.phone);
        
        // Pivot data
        if (user.companies && user.companies.length > 0) {
            const pivot = user.companies[0].pivot;
            $('#role').val(pivot.role);
            $('#is_active').prop('checked', pivot.is_active == 1);
        }

        updateModalUI('view');
        $('#userModal').addClass('active');
    });
});

/* SUPPRESSION */
$(document).on('click', '.deleteBtn', function () {
    deleteId = $(this).data('id');
    $('#deleteModal').addClass('active');
});

$('#confirmDelete').click(() => {
    $.ajax({
        url: `/dashboard/user/destroy/${deleteId}`,
        method: 'PUT',
        data: { _token: "{{ csrf_token() }}" },
        success: () => {
            $('#deleteModal').removeClass('active');
            table.ajax.reload();
        }
    });
});
</script>

@endsection
