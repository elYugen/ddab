<?php

namespace App\Http\Controllers;

use App\Models\Disinfection;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisinfectionController extends Controller
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

        $disinfections = Disinfection::with(['vehicle', 'user'])
            ->where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $disinfections
        ]);
    }

    /**
     * Get vehicles for the company (for select dropdown).
     */
    public function getVehicles()
    {
        $company = Auth::user()->companies()->first();

        if (!$company) {
            return response()->json(['data' => []]);
        }

        $vehicles = Vehicle::where('company_id', $company->id)
            ->where('deleted', 0)
            ->where('in_service', 1)
            ->get(['id', 'name', 'registration_number']);

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
        if (!$company)
            abort(403);

        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'disinfected_at' => 'required|date',
            'type' => 'required|in:daily,weekly,deep',
            'protocol_reference' => 'required|string|max:255',
            'product_used' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Vérifier que le véhicule appartient à la même entreprise
        $vehicle = Vehicle::where('id', $data['vehicle_id'])
            ->where('company_id', $company->id)
            ->first();

        if (!$vehicle) {
            abort(403, 'Véhicule non autorisé');
        }

        $disinfection = Disinfection::create([
            'company_id' => $company->id,
            'user_id' => Auth::id(),
            ...$data
        ]);

        return response()->json([
            'message' => 'Désinfection enregistrée',
            'disinfection' => $disinfection->load(['vehicle', 'user'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Disinfection $disinfection)
    {
        $this->authorizeDisinfection($disinfection);
        return response()->json($disinfection->load(['vehicle', 'user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disinfection $disinfection)
    {
        $this->authorizeDisinfection($disinfection);
        $company = Auth::user()->companies()->first();

        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'disinfected_at' => 'required|date',
            'type' => 'required|in:daily,weekly,deep',
            'protocol_reference' => 'required|string|max:255',
            'product_used' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Vérifier que le véhicule appartient à la même entreprise
        $vehicle = Vehicle::where('id', $data['vehicle_id'])
            ->where('company_id', $company->id)
            ->first();

        if (!$vehicle) {
            abort(403, 'Véhicule non autorisé');
        }

        $disinfection->update($data);

        return response()->json([
            'message' => 'Désinfection mise à jour',
            'disinfection' => $disinfection->load(['vehicle', 'user'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disinfection $disinfection)
    {
        $this->authorizeDisinfection($disinfection);

        $disinfection->delete();

        return response()->json([
            'message' => 'Désinfection supprimée'
        ]);
    }

    /**
     * Sécurité multi-entreprise (retourne seulement la désinfection liée à l'entreprise correspondante à celle de l'user)
     */
    private function authorizeDisinfection(Disinfection $disinfection): void
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $disinfection->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }
}
