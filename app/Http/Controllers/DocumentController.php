<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Vérifie que l'utilisateur est admin ou owner
     */
    private function checkAdminAccess(): void
    {
        $company = Auth::user()->companies()->first();
        if (!$company)
            abort(403);

        $role = strtolower($company->pivot->role);
        if (!in_array($role, ['admin', 'owner'])) {
            abort(403, 'Accès réservé aux administrateurs');
        }
    }

    /**
     * Display all company documents (company docs + user docs).
     */
    public function index()
    {
        $this->checkAdminAccess();
        $company = Auth::user()->companies()->first();

        // Documents de l'entreprise
        $companyDocuments = CompanyDocument::where('company_id', $company->id)
            ->latest()
            ->get()
            ->map(function ($doc) {
                $doc->source = 'company';
                return $doc;
            });

        // Documents des utilisateurs de l'entreprise
        $userDocuments = UserDocument::with('user')
            ->where('company_id', $company->id)
            ->latest()
            ->get()
            ->map(function ($doc) {
                $doc->source = 'user';
                return $doc;
            });

        return response()->json([
            'companyDocuments' => $companyDocuments,
            'userDocuments' => $userDocuments
        ]);
    }

    /**
     * Get only company documents.
     */
    public function companyDocuments()
    {
        $this->checkAdminAccess();
        $company = Auth::user()->companies()->first();

        $documents = CompanyDocument::where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $documents
        ]);
    }

    /**
     * Get all user documents for the company.
     */
    public function allUserDocuments()
    {
        $this->checkAdminAccess();
        $company = Auth::user()->companies()->first();

        $documents = UserDocument::with('user')
            ->where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $documents
        ]);
    }

    /**
     * Store a new company document.
     */
    public function storeCompanyDocument(Request $request)
    {
        $this->checkAdminAccess();
        $company = Auth::user()->companies()->first();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:AGREMENT,ASSURANCE,OTHER',
            'notes' => 'nullable|string|max:500',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = $request->file('file')->store('company_documents/' . $company->id, 'public');

        $document = CompanyDocument::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'document_type' => $data['document_type'],
            'notes' => $data['notes'],
            'file_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Document ajouté',
            'document' => $document
        ], 201);
    }

    /**
     * Show a company document.
     */
    public function showCompanyDocument(CompanyDocument $companyDocument)
    {
        $this->checkAdminAccess();
        $this->authorizeCompanyDocument($companyDocument);
        return response()->json($companyDocument);
    }

    /**
     * Download a company document.
     */
    public function downloadCompanyDocument(CompanyDocument $companyDocument)
    {
        $this->checkAdminAccess();
        $this->authorizeCompanyDocument($companyDocument);

        if (!Storage::disk('public')->exists($companyDocument->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($companyDocument->file_path, $companyDocument->name);
    }

    /**
     * Download a user document (admin access).
     */
    public function downloadUserDocument(UserDocument $userDocument)
    {
        $this->checkAdminAccess();
        $this->authorizeUserDocument($userDocument);

        if (!Storage::disk('public')->exists($userDocument->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($userDocument->file_path, $userDocument->name);
    }

    /**
     * Update a company document.
     */
    public function updateCompanyDocument(Request $request, CompanyDocument $companyDocument)
    {
        $this->checkAdminAccess();
        $this->authorizeCompanyDocument($companyDocument);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:AGREMENT,ASSURANCE,OTHER',
            'notes' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($companyDocument->file_path);
            $data['file_path'] = $request->file('file')->store('company_documents/' . $companyDocument->company_id, 'public');
        }

        unset($data['file']);
        $companyDocument->update($data);

        return response()->json([
            'message' => 'Document mis à jour',
            'document' => $companyDocument
        ]);
    }

    /**
     * Delete a company document.
     */
    public function destroyCompanyDocument(CompanyDocument $companyDocument)
    {
        $this->checkAdminAccess();
        $this->authorizeCompanyDocument($companyDocument);

        Storage::disk('public')->delete($companyDocument->file_path);
        $companyDocument->delete();

        return response()->json([
            'message' => 'Document supprimé'
        ]);
    }

    /**
     * Authorize company document access
     */
    private function authorizeCompanyDocument(CompanyDocument $document): void
    {
        $company = Auth::user()->companies()->first();
        if ($document->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }

    /**
     * Authorize user document access (admin viewing user docs)
     */
    private function authorizeUserDocument(UserDocument $document): void
    {
        $company = Auth::user()->companies()->first();
        if ($document->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }
}
