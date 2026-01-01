<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // entreprise courante (lié à l'utilisateur dans le pivot)
        $company = Auth::user()->companies()->first();

        if (!$company) {
            return response()->json(['data' => []]);
        }

        $vehicles = Vehicle::where('company_id', $company->id)->where('deleted', 0)->get();

        return response()->json([
            'data' => $vehicles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companies()->first();
        if (!$company) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:ambulance,vsl,taxi',
            'category' => 'nullable|string|max:50',
            'registration_number' => 'required|string|max:20|unique:vehicles,registration_number',
            'vin_number' => 'nullable|string|max:50',
            'service_start_date' => 'required|date',
            'service_end_date' => 'nullable|date|after_or_equal:service_start_date',
            'ars_agreement_number' => 'nullable|string|max:50',
            'ars_agreement_start_date' => 'nullable|date',
            'ars_agreement_end_date' => 'nullable|date|after_or_equal:ars_agreement_start_date',
            'in_service' => 'boolean',
        ]);

        $vehicle = Vehicle::create([
            'company_id' => $company->id,
            'deleted' => 0,
            ...$data
        ]);

        return response()->json([
            'message' => 'Véhicule créé',
            'vehicle' => $vehicle
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        return response()->json($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:ambulance,vsl,taxi',
            'category' => 'nullable|string|max:50',
            'registration_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'registration_number')->ignore($vehicle->id)
            ],
            'vin_number' => 'nullable|string|max:50',
            'service_start_date' => 'required|date',
            'service_end_date' => 'nullable|date|after_or_equal:service_start_date',
            'ars_agreement_number' => 'nullable|string|max:50',
            'ars_agreement_start_date' => 'nullable|date',
            'ars_agreement_end_date' => 'nullable|date|after_or_equal:ars_agreement_start_date',
            'in_service' => 'boolean',
        ]);

        $vehicle->update($data);

        return response()->json([
            'message' => 'Véhicule mis à jour',
            'vehicle' => $vehicle
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $vehicle->update([
            'deleted' => 1
        ]);

        return response()->json([
            'message' => 'Véhicule supprimé'
        ]);
    }

    /**
     * Sécurité multi-entreprise (retourne seulement le véhicule lié à l'entreprise correspondante à celle de l'user)
     */
    private function authorizeVehicle(Vehicle $vehicle): void
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $vehicle->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }
}
