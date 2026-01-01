<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
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

        $patients = Patient::where('company_id', $company->id)->where('deleted', 0)->latest()->get();

        return response()->json([
            'data' => $patients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companies()->first();

        if (!$company) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'social_security_number' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $patient = Patient::create(array_merge($validated, [
            'company_id' => $company->id,
            'deleted' => 0,
        ]));

        return response()->json([
            'message' => 'Patient créé avec succès',
            'patient' => $patient
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $this->authorizePatient($patient);
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $this->authorizePatient($patient);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'social_security_number' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return response()->json([
            'message' => 'Patient mis à jour',
            'patient' => $patient
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $this->authorizePatient($patient);

        $patient->update([
            'deleted' => 1
        ]);

        return response()->json([
            'message' => 'Patient supprimé'
        ]);
    }

    /**
     * Sécurité multi-entreprise (retourne seulement le patient lié à l'entreprise correspondante à celle de l'user)
     */
    private function authorizePatient(Patient $patient): void
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $patient->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }
}
