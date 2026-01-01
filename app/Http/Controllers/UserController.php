<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->companies()->first();

        if (!$company) {
            abort(403);
        }

        $users = $company->users()
            ->where('users.deleted', 0)
            ->get();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        $company = $authUser->companies()->first();

        if (!$company) {
            abort(403);
        }

        // rôle du créateur dans l'entreprise
        $creatorRole = $authUser->companies()
            ->where('company_id', $company->id)
            ->first()
            ->pivot
            ->role;

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
            'role'       => ['required', Rule::in(['admin', 'employee'])],
        ]);

        // 🔐 Sécurité des rôles
        if ($creatorRole === 'employee') {
            abort(403, 'Un salarié ne peut pas créer de compte');
        }

        if ($creatorRole === 'admin' && $request->role !== 'employee') {
            abort(403, 'Un admin ne peut créer que des salariés');
        }

        // Création utilisateur
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'deleted'    => 0,
        ]);

        // Liaison entreprise
        $user->companies()->attach($company->id, [
            'role' => $request->role,
            'is_active' => 1
        ]);

        return response()->json([
            'message' => 'Utilisateur créé',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorizeUser($user);
        
        $company = Auth::user()->companies()->first();
        $user->load(['companies' => function($query) use ($company) {
            $query->where('companies.id', $company->id);
        }]);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        $company = $authUser->companies()->first();

        $creatorRole = $authUser->companies()
            ->where('company_id', $company->id)
            ->first()
            ->pivot
            ->role;

        if (!$user->companies()->where('company_id', $company->id)->exists()) {
            abort(403);
        }

        // ADMIN ne peut pas modifier un ADMIN
        if (
            $creatorRole === 'admin' &&
            $user->companies()->wherePivot('role', 'admin')->exists()
        ) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:8',
            'is_active'  => 'boolean',
        ]);

        $user->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'email',
        ]));

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->has('is_active')) {
            $user->companies()->updateExistingPivot($company->id, [
                'is_active' => $request->is_active
            ]);
        }

        return response()->json(['message' => 'Utilisateur mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $authUser = Auth::user();
        $company = $authUser->companies()->first();

        $creatorRole = $authUser->companies()
            ->where('company_id', $company->id)
            ->first()
            ->pivot
            ->role;

        if (!$user->companies()->where('company_id', $company->id)->exists()) {
            abort(403);
        }

        // ADMIN ne peut pas supprimer un ADMIN ou OWNER
        if (
            $creatorRole === 'admin' &&
            $user->companies()->wherePivotIn('role', ['admin', 'owner'])->exists()
        ) {
            abort(403);
        }

        $user->companies()->updateExistingPivot($company->id, [
            'is_active' => 0
        ]);

        $user->update(['deleted' => 1]);

        return response()->json(['message' => 'Utilisateur désactivé']);
    }

    private function authorizeUser(User $user): void
    {
        $company = Auth::user()->companies()->first();

        if (
            !$company ||
            !$user->companies()->where('company_id', $company->id)->exists()
        ) {
            abort(403);
        }
    }
}
