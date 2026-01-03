<?php

namespace App\Http\Controllers;

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDocumentController extends Controller
{
    /**
     * Display a listing of the current user's documents.
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->companies()->first();

        if (!$company) {
            return response()->json(['data' => []]);
        }

        $documents = UserDocument::where('user_id', $user->id)
            ->where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $documents
        ]);
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        if (!$company)
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:AFGSU,DIPLOME,LICENSE,OTHER',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = $request->file('file')->store('user_documents/' . $user->id, 'public');

        $document = UserDocument::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'name' => $data['name'],
            'document_type' => $data['document_type'],
            'file_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Document ajouté',
            'document' => $document
        ], 201);
    }

    /**
     * Display the specified document.
     */
    public function show(UserDocument $userDocument)
    {
        $this->authorizeDocument($userDocument);
        return response()->json($userDocument);
    }

    /**
     * Download the document file.
     */
    public function download(UserDocument $userDocument)
    {
        $this->authorizeDocument($userDocument);

        if (!Storage::disk('public')->exists($userDocument->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($userDocument->file_path, $userDocument->name);
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, UserDocument $userDocument)
    {
        $this->authorizeDocument($userDocument);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:AFGSU,DIPLOME,LICENSE,OTHER',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            Storage::disk('public')->delete($userDocument->file_path);
            $data['file_path'] = $request->file('file')->store('user_documents/' . Auth::id(), 'public');
        }

        unset($data['file']);
        $userDocument->update($data);

        return response()->json([
            'message' => 'Document mis à jour',
            'document' => $userDocument
        ]);
    }

    /**
     * Remove the specified document.
     */
    public function destroy(UserDocument $userDocument)
    {
        $this->authorizeDocument($userDocument);

        // Supprimer le fichier
        Storage::disk('public')->delete($userDocument->file_path);

        $userDocument->delete();

        return response()->json([
            'message' => 'Document supprimé'
        ]);
    }

    /**
     * L'utilisateur ne peut accéder qu'à ses propres documents
     */
    private function authorizeDocument(UserDocument $document): void
    {
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Accès interdit');
        }
    }
}
