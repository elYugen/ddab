@extends('base')
@section('title', 'Courses / Transports')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <style>
        :root {
            --fc-border-color: #e5e7eb;
            --fc-button-bg-color: #6366f1;
            --fc-button-active-bg-color: #4f46e5;
            --fc-button-hover-bg-color: #5558e8;
            --fc-today-bg-color: #eef2ff;
        }

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
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
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

        .transport-type-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .transport-type-badge.ambulance {
            background: #fee2e2;
            color: #dc2626;
        }

        .transport-type-badge.vsl {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .emergency-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        #calendar {
            min-height: 700px;
        }

        .fc .fc-button {
            border-radius: 8px !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
        }

        .fc .fc-toolbar-title {
            font-size: 1.3rem !important;
            font-weight: 700 !important;
        }

        .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 12px !important;
            cursor: pointer;
        }

        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .legend {
            display: flex;
            gap: 24px;
            margin-bottom: 16px;
            padding: 12px 16px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4b5563;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.ambulance {
            background: #ef4444;
        }

        .legend-color.vsl {
            background: #3b82f6;
        }

        .map-container {
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 12px;
            background: #e5e7eb;
        }

        .address-input-group {
            position: relative;
        }

        .address-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            display: none;
        }

        .address-suggestion {
            padding: 10px 16px;
            cursor: pointer;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }

        .address-suggestion:hover {
            background: #f3f4f6;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
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
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                    Planning des transports
                </h1>
                <button id="addTransportBtn" class="btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle course
                </button>
            </div>

            <div class="card">
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color ambulance"></div>
                        <span>Ambulance</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color vsl"></div>
                        <span>VSL</span>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- MODAL TRANSPORT -->
    <div id="transportModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-2xl rounded-2xl p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div id="modalIcon" class="modal-icon add">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Nouvelle course</h2>
                </div>
            </div>

            <form id="transportForm">
                <input type="hidden" id="transportId">

                <!-- Type de transport -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Type de transport</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-500"
                            id="typeVslLabel">
                            <input type="radio" name="transport_type" value="VSL" class="hidden" id="typeVsl" checked>
                            <div class="text-center">
                                <div class="text-2xl mb-1">🚗</div>
                                <span class="font-semibold text-blue-600">VSL</span>
                            </div>
                        </label>
                        <label
                            class="flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-red-500"
                            id="typeAmbulanceLabel">
                            <input type="radio" name="transport_type" value="AMBULANCE" class="hidden" id="typeAmbulance">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🚑</div>
                                <span class="font-semibold text-red-600">Ambulance</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Patient -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Patient</label>
                    <select id="patient_id" required>
                        <option value="">Sélectionner un patient...</option>
                    </select>
                </div>

                <!-- Date et heures -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Date</label>
                        <input type="date" id="transport_date" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Heure départ</label>
                        <input type="time" id="start_time">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Heure arrivée</label>
                        <input type="time" id="end_time">
                    </div>
                </div>

                <!-- Équipage -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Chauffeur</label>
                        <select id="driver_id" required>
                            <option value="">Sélectionner...</option>
                        </select>
                    </div>
                    <div id="assistantField">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Assistant (optionnel)</label>
                        <select id="assistant_id">
                            <option value="">Aucun</option>
                        </select>
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Véhicule</label>
                    <select id="vehicle_id" required>
                        <option value="">Sélectionner...</option>
                    </select>
                </div>

                <!-- Adresses -->
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div class="address-input-group">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Adresse de départ</label>
                        <input type="text" id="pickup_address" placeholder="Rechercher une adresse..." required
                            autocomplete="off">
                        <div id="pickup_suggestions" class="address-suggestions"></div>
                    </div>
                    <div class="address-input-group">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Adresse de destination</label>
                        <input type="text" id="destination_address" placeholder="Rechercher une adresse..." required
                            autocomplete="off">
                        <div id="destination_suggestions" class="address-suggestions"></div>
                    </div>
                </div>

                <!-- Distance -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Distance (km)</label>
                        <input type="number" id="distance_km" step="0.1" min="0" placeholder="Auto-calculé">
                    </div>
                    <div class="flex items-end">
                        <label
                            class="flex items-center gap-3 cursor-pointer p-3 bg-red-50 rounded-xl border-2 border-red-200 w-full">
                            <input type="checkbox" id="is_emergency" class="w-5 h-5 accent-red-600">
                            <span class="font-semibold text-red-700">🚨 Urgence</span>
                        </label>
                    </div>
                </div>

                <!-- Map preview -->
                <div id="mapContainer" class="map-container mb-4" style="display:none;">
                    <div id="map" style="width:100%; height:100%;"></div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" id="closeModal" class="btn-secondary">Fermer</button>
                    <button type="button" id="editBtn" class="btn-secondary" style="display:none;">Modifier</button>
                    <button type="submit" id="submitBtn" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DÉTAILS TRANSPORT -->
    <div id="detailsModal"
        class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon view">
                        <svg class="icon" style="width:24px;height:24px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="detailsTitle" class="text-xl font-bold text-gray-900">Détails du transport</h2>
                        <div id="detailsBadges" class="flex gap-2 mt-1"></div>
                    </div>
                </div>
            </div>

            <div id="detailsContent">
                <!-- Contenu dynamique -->
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="closeDetails" class="btn-secondary">Fermer</button>
                <button type="button" id="editFromDetails" class="btn-primary">Modifier</button>
                <button type="button" id="deleteFromDetails" class="btn-danger">Supprimer</button>
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
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer ce transport ?</p>
            <div class="flex justify-end gap-3">
                <button id="cancelDelete" class="btn-secondary">Annuler</button>
                <button id="confirmDelete" class="btn-danger">Supprimer</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js"></script>

    <script>
        let calendar;
        let currentTransportId = null;
        let isViewMode = false;

        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: {
                    url: '{{ route("dashboard.transport.calendar") }}',
                    method: 'GET'
                },
                eventClick: function (info) {
                    showTransportDetails(info.event.extendedProps.transport_id);
                },
                eventDrop: function (info) {
                    // Drag-drop pour changer la date
                    updateTransportDate(info.event.extendedProps.transport_id, info.event.start);
                },
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                select: function (info) {
                    // Clic sur une date pour créer
                    openAddModal(info.startStr);
                }
            });

            calendar.render();
            loadFormData();
        });

        // Load dropdown data
        function loadFormData() {
            $.get('{{ route("dashboard.transport.patients") }}', function (res) {
                let html = '<option value="">Sélectionner un patient...</option>';
                res.data.forEach(p => {
                    html += `<option value="${p.id}">${p.first_name} ${p.last_name}</option>`;
                });
                $('#patient_id').html(html);
            });

            $.get('{{ route("dashboard.transport.drivers") }}', function (res) {
                let html = '<option value="">Sélectionner...</option>';
                let htmlAssist = '<option value="">Aucun</option>';
                res.data.forEach(d => {
                    html += `<option value="${d.id}">${d.first_name} ${d.last_name}</option>`;
                    htmlAssist += `<option value="${d.id}">${d.first_name} ${d.last_name}</option>`;
                });
                $('#driver_id').html(html);
                $('#assistant_id').html(htmlAssist);
            });

            $.get('{{ route("dashboard.transport.vehicles") }}', function (res) {
                let html = '<option value="">Sélectionner...</option>';
                res.data.forEach(v => {
                    html += `<option value="${v.id}">${v.name} (${v.registration_number})</option>`;
                });
                $('#vehicle_id').html(html);
            });
        }

        // Transport type radio handling
        $('input[name="transport_type"]').change(function () {
            $('#typeVslLabel, #typeAmbulanceLabel').removeClass('border-blue-500 border-red-500 bg-blue-50 bg-red-50');
            if ($(this).val() === 'VSL') {
                $('#typeVslLabel').addClass('border-blue-500 bg-blue-50');
                $('#assistantField').hide();
            } else {
                $('#typeAmbulanceLabel').addClass('border-red-500 bg-red-50');
                $('#assistantField').show();
            }
        });
        $('#typeVsl').trigger('change');

        // Modal handling
        function openAddModal(date = null) {
            $('#modalTitle').text('Nouvelle course');
            $('#transportForm')[0].reset();
            $('#transportId').val('');
            $('#typeVsl').prop('checked', true).trigger('change');

            if (date) {
                $('#transport_date').val(date);
            } else {
                $('#transport_date').val(new Date().toISOString().split('T')[0]);
            }

            setFormDisabled(false);
            $('#editBtn').hide();
            $('#submitBtn').show();
            $('#transportModal').addClass('active');
        }

        $('#addTransportBtn').click(() => openAddModal());
        $('#closeModal').click(() => $('#transportModal').removeClass('active'));
        $('#closeDetails').click(() => $('#detailsModal').removeClass('active'));
        $('#cancelDelete').click(() => $('#deleteModal').removeClass('active'));

        function setFormDisabled(disabled) {
            $('#transportForm input:not([type="hidden"]), #transportForm select').prop('disabled', disabled);
            isViewMode = disabled;
        }

        // Show transport details
        function showTransportDetails(id) {
            currentTransportId = id;
            $.get(`/dashboard/transport/get/${id}`, function (transport) {
                const typeBadge = transport.transport_type === 'AMBULANCE'
                    ? '<span class="transport-type-badge ambulance">🚑 Ambulance</span>'
                    : '<span class="transport-type-badge vsl">🚗 VSL</span>';

                const emergencyBadge = transport.is_emergency
                    ? '<span class="emergency-badge">🚨 URGENCE</span>'
                    : '';

                $('#detailsBadges').html(typeBadge + ' ' + emergencyBadge);

                const patientName = transport.patient ? `${transport.patient.first_name} ${transport.patient.last_name}` : 'N/A';
                const driverName = transport.driver ? `${transport.driver.first_name} ${transport.driver.last_name}` : 'N/A';
                const assistantName = transport.assistant ? `${transport.assistant.first_name} ${transport.assistant.last_name}` : 'Aucun';
                const vehicleName = transport.vehicle ? `${transport.vehicle.name} (${transport.vehicle.registration_number})` : 'N/A';

                const date = new Date(transport.transport_date).toLocaleDateString('fr-FR', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });

                const startTime = transport.start_time ? new Date(transport.start_time).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '--:--';
                const endTime = transport.end_time ? new Date(transport.end_time).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '--:--';

                let html = `
                <div class="info-row">
                    <div class="info-icon">👤</div>
                    <div class="info-content">
                        <div class="info-label">Patient</div>
                        <div class="info-value">${patientName}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">📅</div>
                    <div class="info-content">
                        <div class="info-label">Date & Horaires</div>
                        <div class="info-value">${date} • ${startTime} → ${endTime}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">🚗</div>
                    <div class="info-content">
                        <div class="info-label">Véhicule</div>
                        <div class="info-value">${vehicleName}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">👨‍✈️</div>
                    <div class="info-content">
                        <div class="info-label">Chauffeur</div>
                        <div class="info-value">${driverName}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">👥</div>
                    <div class="info-content">
                        <div class="info-label">Assistant</div>
                        <div class="info-value">${assistantName}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">📍</div>
                    <div class="info-content">
                        <div class="info-label">Départ</div>
                        <div class="info-value">${transport.pickup_address}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">🏁</div>
                    <div class="info-content">
                        <div class="info-label">Destination</div>
                        <div class="info-value">${transport.destination_address}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">📏</div>
                    <div class="info-content">
                        <div class="info-label">Distance</div>
                        <div class="info-value">${parseFloat(transport.distance_km || 0).toFixed(1)} km</div>
                    </div>
                </div>
            `;

                $('#detailsContent').html(html);
                $('#detailsModal').addClass('active');
            });
        }

        // Edit from details
        $('#editFromDetails').click(function () {
            $('#detailsModal').removeClass('active');

            $.get(`/dashboard/transport/get/${currentTransportId}`, function (transport) {
                $('#modalTitle').text('Modifier le transport');
                $('#transportId').val(transport.id);
                $('#patient_id').val(transport.patient_id);
                $('#driver_id').val(transport.driver_id);
                $('#assistant_id').val(transport.assistant_id || '');
                $('#vehicle_id').val(transport.vehicle_id);
                $('#pickup_address').val(transport.pickup_address);
                $('#destination_address').val(transport.destination_address);
                $('#distance_km').val(transport.distance_km);
                $('#is_emergency').prop('checked', transport.is_emergency);

                if (transport.transport_date) {
                    $('#transport_date').val(transport.transport_date.split('T')[0]);
                }
                if (transport.start_time) {
                    $('#start_time').val(new Date(transport.start_time).toTimeString().slice(0, 5));
                }
                if (transport.end_time) {
                    $('#end_time').val(new Date(transport.end_time).toTimeString().slice(0, 5));
                }

                if (transport.transport_type === 'AMBULANCE') {
                    $('#typeAmbulance').prop('checked', true).trigger('change');
                } else {
                    $('#typeVsl').prop('checked', true).trigger('change');
                }

                setFormDisabled(false);
                $('#editBtn').hide();
                $('#submitBtn').show();
                $('#transportModal').addClass('active');
            });
        });

        // Delete from details
        $('#deleteFromDetails').click(function () {
            $('#detailsModal').removeClass('active');
            $('#deleteModal').addClass('active');
        });

        $('#confirmDelete').click(function () {
            $.ajax({
                url: `/dashboard/transport/destroy/${currentTransportId}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    $('#deleteModal').removeClass('active');
                    calendar.refetchEvents();
                }
            });
        });

        // Form submission
        $('#transportForm').submit(function (e) {
            e.preventDefault();
            if (isViewMode) return;

            const id = $('#transportId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/dashboard/transport/update/${id}` : '/dashboard/transport/store';

            $.ajax({
                url: url,
                method: method,
                data: {
                    patient_id: $('#patient_id').val(),
                    driver_id: $('#driver_id').val(),
                    assistant_id: $('#assistant_id').val() || null,
                    vehicle_id: $('#vehicle_id').val(),
                    transport_type: $('input[name="transport_type"]:checked').val(),
                    pickup_address: $('#pickup_address').val(),
                    destination_address: $('#destination_address').val(),
                    distance_km: $('#distance_km').val() || 0,
                    transport_date: $('#transport_date').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    is_emergency: $('#is_emergency').is(':checked'),
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    $('#transportModal').removeClass('active');
                    calendar.refetchEvents();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Erreur lors de l\'enregistrement');
                }
            });
        });

        // Update date via drag-drop
        function updateTransportDate(id, newDate) {
            $.ajax({
                url: `/dashboard/transport/update-date/${id}`,
                method: 'PATCH',
                data: {
                    transport_date: newDate.toISOString().split('T')[0],
                    _token: '{{ csrf_token() }}'
                },
                error: function () {
                    calendar.refetchEvents();
                }
            });
        }

        // Address autocomplete using Nominatim (OpenStreetMap - gratuit)
        let searchTimeout;
        function setupAddressAutocomplete(inputId, suggestionsId) {
            const input = document.getElementById(inputId);
            const suggestions = document.getElementById(suggestionsId);

            input.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const query = this.value;

                if (query.length < 3) {
                    suggestions.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=fr&limit=5`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                suggestions.innerHTML = data.map(item =>
                                    `<div class="address-suggestion" data-address="${item.display_name}">${item.display_name}</div>`
                                ).join('');
                                suggestions.style.display = 'block';
                            } else {
                                suggestions.style.display = 'none';
                            }
                        });
                }, 300);
            });

            suggestions.addEventListener('click', function (e) {
                if (e.target.classList.contains('address-suggestion')) {
                    input.value = e.target.dataset.address;
                    suggestions.style.display = 'none';
                }
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.style.display = 'none';
                }
            });
        }

        setupAddressAutocomplete('pickup_address', 'pickup_suggestions');
        setupAddressAutocomplete('destination_address', 'destination_suggestions');
    </script>
@endsection