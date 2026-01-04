<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    /**
     * Get all transports for calendar display
     */
    public function index()
    {
        $company = Auth::user()->companies()->first();
        if (!$company) {
            return response()->json(['data' => []]);
        }

        $transports = Transport::with(['patient', 'driver', 'assistant', 'vehicle'])
            ->where('company_id', $company->id)
            ->orderBy('transport_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json(['data' => $transports]);
    }

    /**
     * Get transports for a specific date range (calendar)
     */
    public function calendar(Request $request)
    {
        $company = Auth::user()->companies()->first();
        if (!$company) {
            return response()->json([]);
        }

        $start = $request->get('start');
        $end = $request->get('end');

        $transports = Transport::with(['patient', 'driver', 'assistant', 'vehicle'])
            ->where('company_id', $company->id)
            ->when($start, fn($q) => $q->where('transport_date', '>=', $start))
            ->when($end, fn($q) => $q->where('transport_date', '<=', $end))
            ->get();

        // Format for FullCalendar
        $events = $transports->map(function ($transport) {
            $patientName = $transport->patient
                ? $transport->patient->first_name . ' ' . $transport->patient->last_name
                : 'Patient inconnu';

            $driverName = $transport->driver
                ? $transport->driver->first_name . ' ' . $transport->driver->last_name
                : 'Non assigné';

            $startDateTime = $transport->transport_date->format('Y-m-d');
            if ($transport->start_time) {
                $startDateTime = $transport->transport_date->format('Y-m-d') . 'T' .
                    \Carbon\Carbon::parse($transport->start_time)->format('H:i:s');
            }

            $endDateTime = null;
            if ($transport->end_time) {
                $endDateTime = $transport->transport_date->format('Y-m-d') . 'T' .
                    \Carbon\Carbon::parse($transport->end_time)->format('H:i:s');
            }

            return [
                'id' => $transport->id,
                'title' => ($transport->start_time 
                    ? \Carbon\Carbon::parse($transport->start_time)->format('H:i') . ' - ' 
                    : '') . $patientName . ' (' . $transport->transport_type . ')',
                'start' => $startDateTime,
                'end' => $endDateTime,
                'backgroundColor' => $transport->transport_type === 'AMBULANCE' ? '#ef4444' : '#3b82f6',
                'borderColor' => $transport->transport_type === 'AMBULANCE' ? '#dc2626' : '#2563eb',
                'extendedProps' => [
                    'transport_id' => $transport->id,
                    'patient_name' => $patientName,
                    'driver_name' => $driverName,
                    'vehicle' => $transport->vehicle ? $transport->vehicle->name : 'Non assigné',
                    'transport_type' => $transport->transport_type,
                    'pickup_address' => $transport->pickup_address,
                    'destination_address' => $transport->destination_address,
                    'is_emergency' => $transport->is_emergency,
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Get available drivers (employees of the company)
     */
    public function getDrivers()
    {
        $company = Auth::user()->companies()->first();
        if (!$company) {
            return response()->json(['data' => []]);
        }

        $drivers = $company->users()
            ->where('deleted', 0)
            ->select('users.id', 'users.first_name', 'users.last_name')
            ->get();

        return response()->json(['data' => $drivers]);
    }

    /**
     * Get available vehicles (in service)
     */
    public function getVehicles()
    {
        $company = Auth::user()->companies()->first();
        if (!$company) {
            return response()->json(['data' => []]);
        }

        $vehicles = Vehicle::where('company_id', $company->id)
            ->where('in_service', 1)
            ->where('deleted', 0)
            ->select('id', 'name', 'type', 'registration_number')
            ->get();

        return response()->json(['data' => $vehicles]);
    }

    /**
     * Get patients
     */
    public function getPatients()
    {
        $company = Auth::user()->companies()->first();
        if (!$company) {
            return response()->json(['data' => []]);
        }

        $patients = Patient::where('company_id', $company->id)
            ->where('deleted', 0)
            ->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json(['data' => $patients]);
    }

    /**
     * Store a newly created transport.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companies()->first();
        if (!$company)
            abort(403);

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'driver_id' => 'required|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'transport_type' => 'required|in:VSL,AMBULANCE',
            'pickup_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'distance_km' => 'nullable|numeric|min:0',
            'transport_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'is_emergency' => 'nullable|boolean',
        ]);

        // Vérifier que le patient appartient à l'entreprise
        $patient = Patient::where('id', $data['patient_id'])
            ->where('company_id', $company->id)
            ->first();
        if (!$patient) {
            return response()->json(['message' => 'Patient non trouvé'], 404);
        }

        // Vérifier que le véhicule appartient à l'entreprise
        $vehicle = Vehicle::where('id', $data['vehicle_id'])
            ->where('company_id', $company->id)
            ->first();
        if (!$vehicle) {
            return response()->json(['message' => 'Véhicule non trouvé'], 404);
        }

        $transport = Transport::create([
            'company_id' => $company->id,
            'patient_id' => $data['patient_id'],
            'driver_id' => $data['driver_id'],
            'assistant_id' => $data['assistant_id'] ?? null,
            'vehicle_id' => $data['vehicle_id'],
            'transport_type' => $data['transport_type'],
            'pickup_address' => $data['pickup_address'],
            'destination_address' => $data['destination_address'],
            'distance_km' => $data['distance_km'] ?? 0,
            'transport_date' => $data['transport_date'],
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'is_emergency' => $data['is_emergency'] ?? false,
        ]);

        return response()->json([
            'message' => 'Transport créé',
            'transport' => $transport->load(['patient', 'driver', 'assistant', 'vehicle'])
        ], 201);
    }

    /**
     * Display the specified transport.
     */
    public function show(Transport $transport)
    {
        $this->authorizeTransport($transport);
        return response()->json($transport->load(['patient', 'driver', 'assistant', 'vehicle']));
    }

    /**
     * Update the specified transport.
     */
    public function update(Request $request, Transport $transport)
    {
        $this->authorizeTransport($transport);
        $company = Auth::user()->companies()->first();

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'driver_id' => 'required|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'transport_type' => 'required|in:VSL,AMBULANCE',
            'pickup_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'distance_km' => 'nullable|numeric|min:0',
            'transport_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'is_emergency' => 'nullable|boolean',
        ]);

        // Préparer les données de mise à jour
        $updateData = [
            'patient_id' => $data['patient_id'],
            'driver_id' => $data['driver_id'],
            'assistant_id' => $data['assistant_id'] ?? null,
            'vehicle_id' => $data['vehicle_id'],
            'transport_type' => $data['transport_type'],
            'pickup_address' => $data['pickup_address'],
            'destination_address' => $data['destination_address'],
            'distance_km' => $data['distance_km'] ?? 0,
            'transport_date' => $data['transport_date'],
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'is_emergency' => $data['is_emergency'] ?? false,
        ];

        $transport->update($updateData);

        return response()->json([
            'message' => 'Transport mis à jour',
            'transport' => $transport->fresh()->load(['patient', 'driver', 'assistant', 'vehicle'])
        ]);
    }

    /**
     * Remove the specified transport.
     */
    public function destroy(Transport $transport)
    {
        $this->authorizeTransport($transport);
        $transport->delete();

        return response()->json([
            'message' => 'Transport supprimé'
        ]);
    }

    /**
     * Update transport via calendar drag-drop
     */
    public function updateDate(Request $request, Transport $transport)
    {
        $this->authorizeTransport($transport);

        $data = $request->validate([
            'transport_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        $updateData = [
            'transport_date' => $data['transport_date'],
        ];

        if (!empty($data['start_time'])) {
            $updateData['start_time'] = $data['start_time'];
        }

        if (!empty($data['end_time'])) {
            $updateData['end_time'] = $data['end_time'];
        }

        $transport->update($updateData);

        return response()->json([
            'message' => 'Date mise à jour',
            'transport' => $transport->fresh()
        ]);
    }

    /**
     * Sécurité multi-entreprise
     */
    private function authorizeTransport(Transport $transport): void
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $transport->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }


}
