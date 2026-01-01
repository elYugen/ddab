@extends('base')
@section('title', 'Gestion des patients')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
.input {
    width: 100%;
    padding: 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}
</style>
@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    @include('layout.sidebar')

    <div class="flex-1 overflow-y-auto p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des patients</h1>

        <button id="addPatientBtn"
    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
    + Ajouter un patient
</button>

        <div class="bg-white shadow rounded-lg p-6">
            <table id="patientsTable" class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Téléphone</th>
                        <th>Sécurité sociale</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AJOUT --->
<div id="patientModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg p-6">
        <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>

        <form id="patientForm">
            <input type="hidden" id="patientId">

            <div class="grid grid-cols-2 gap-4">
                <input id="last_name" placeholder="Nom" class="input" required>
                <input id="first_name" placeholder="Prénom" class="input" required>
            </div>

            <input id="birth_date" type="date" class="input mt-3">
            <input id="phone" placeholder="Téléphone" class="input mt-3">
            <input id="social_security_number" placeholder="N° Sécurité sociale" class="input mt-3">

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                <input id="street" placeholder="Rue (ex: 14 rue du Commerce)" class="input">
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <input id="postal_code" placeholder="Code postal" class="input" maxlength="10">
                    <input id="city" placeholder="Ville" class="input">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- modal suppression -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Confirmation</h2>
        <p>Confirmer la suppression du patient ?</p>

        <div class="flex justify-end gap-3 mt-6">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded">
                Supprimer
            </button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
let table;

$(document).ready(function () {
    table = $('#patientsTable').DataTable({
        processing: true,
        ajax: {
            url: "{{ route('dashboard.patient.index') }}",
            type: "GET",
        },
        columns: [
            { data: 'last_name' },
            { data: 'first_name' },
            { 
                data: 'birth_date',
                render: data => data ? new Date(data).toLocaleDateString('fr-FR') : ''
            },
            { data: 'phone' },
            { data: 'social_security_number' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <button class="viewBtn" data-id="${data}">Voir</button>
                        <button class="editBtn" data-id="${data}">Éditer</button>
                        <button class="deleteBtn" data-id="${data}">Supprimer</button>
                    `;
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
        }
    });
});
</script>
<script>
let deleteId = null;

/* OUVERTURE MODAL AJOUT */
$('#addPatientBtn').click(() => {
    $('#modalTitle').text('Ajouter un patient');
    $('#patientForm')[0].reset();
    $('#patientId').val('');
    $('#patientModal').removeClass('hidden');
});

/* FERMETURE MODALES */
$('#closeModal').click(() => $('#patientModal').addClass('hidden'));
$('#cancelDelete').click(() => $('#deleteModal').addClass('hidden'));

/* CRÉATION / ÉDITION */
$('#patientForm').submit(function (e) {
    e.preventDefault();

    const id = $('#patientId').val();
    const method = id ? 'PUT' : 'POST';
    const url = id
        ? `/dashboard/patient/update/${id}`
        : `/dashboard/patient/store`;

    $.ajax({
        url,
        method,
        data: {
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            birth_date: $('#birth_date').val(),
            phone: $('#phone').val(),
            social_security_number: $('#social_security_number').val(),
            street: $('#street').val(),
            postal_code: $('#postal_code').val(),
            city: $('#city').val(),
            _token: "{{ csrf_token() }}"
        },
        success: () => {
            $('#patientModal').addClass('hidden');
            table.ajax.reload();
        }
    });
});

/* VOIR / ÉDITER */
$(document).on('click', '.viewBtn, .editBtn', function () {
    const id = $(this).data('id');

    $.get(`/dashboard/patient/get/${id}`, function (patient) {
        $('#modalTitle').text('Éditer le patient');
        $('#patientId').val(patient.id);
        $('#first_name').val(patient.first_name);
        $('#last_name').val(patient.last_name);
        // Formater la date au format YYYY-MM-DD pour l'input date
        if (patient.birth_date) {
            const date = new Date(patient.birth_date);
            const formatted = date.toISOString().split('T')[0];
            $('#birth_date').val(formatted);
        } else {
            $('#birth_date').val('');
        }
        $('#phone').val(patient.phone);
        $('#social_security_number').val(patient.social_security_number);
        $('#street').val(patient.street);
        $('#postal_code').val(patient.postal_code);
        $('#city').val(patient.city);

        $('#patientModal').removeClass('hidden');
    });
});

/* SUPPRESSION */
$(document).on('click', '.deleteBtn', function () {
    deleteId = $(this).data('id');
    $('#deleteModal').removeClass('hidden');
});

$('#confirmDelete').click(() => {
    $.ajax({
        url: `/dashboard/patient/destroy/${deleteId}`,
        method: 'PUT',
        data: { _token: "{{ csrf_token() }}" },
        success: () => {
            $('#deleteModal').addClass('hidden');
            table.ajax.reload();
        }
    });
});
</script>

@endsection
