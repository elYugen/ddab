<?php

namespace App\Http\Controllers;

use App\Models\StockItems;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    /**
     * Display a listing of stock items.
     */
    public function index()
    {
        $company = Auth::user()->companies()->first();

        if (!$company) {
            return response()->json(['data' => []]);
        }

        $items = StockItems::where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $items
        ]);
    }

    /**
     * Get stock movements for a specific item.
     */
    public function movements(StockItems $stockItem)
    {
        $this->authorizeStockItem($stockItem);

        $movements = StockMovement::with('user')
            ->where('stock_item_id', $stockItem->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $movements
        ]);
    }

    /**
     * Store a newly created stock item.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companies()->first();
        if (!$company)
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'reorder_threshold' => 'nullable|numeric|min:0',
            'picture' => 'nullable|image|max:2048',
        ]);

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('stock_pictures', 'public');
        }

        $stockItem = StockItems::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'unit' => $data['unit'],
            'quantity' => $data['quantity'],
            'reorder_threshold' => $data['reorder_threshold'] ?? 0,
            'picture_file_path' => $picturePath,
        ]);

        // Enregistrer le mouvement initial si quantité > 0
        if ($data['quantity'] > 0) {
            StockMovement::create([
                'user_id' => Auth::id(),
                'company_id' => $company->id,
                'stock_item_id' => $stockItem->id,
                'type' => 'IN',
                'quantity' => $data['quantity'],
                'reason' => 'Stock initial',
            ]);
        }

        return response()->json([
            'message' => 'Article créé',
            'stockItem' => $stockItem
        ], 201);
    }

    /**
     * Display the specified stock item.
     */
    public function show(StockItems $stockItem)
    {
        $this->authorizeStockItem($stockItem);
        return response()->json($stockItem);
    }

    /**
     * Update the specified stock item.
     */
    public function update(Request $request, StockItems $stockItem)
    {
        $this->authorizeStockItem($stockItem);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'reorder_threshold' => 'nullable|numeric|min:0',
            'picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            // Supprimer l'ancienne image
            if ($stockItem->picture_file_path) {
                Storage::disk('public')->delete($stockItem->picture_file_path);
            }
            $data['picture_file_path'] = $request->file('picture')->store('stock_pictures', 'public');
        }

        unset($data['picture']);
        $stockItem->update($data);

        return response()->json([
            'message' => 'Article mis à jour',
            'stockItem' => $stockItem
        ]);
    }

    /**
     * Add stock movement (IN or OUT).
     */
    public function addMovement(Request $request, StockItems $stockItem)
    {
        $this->authorizeStockItem($stockItem);
        $company = Auth::user()->companies()->first();

        $data = $request->validate([
            'type' => 'required|in:IN,OUT',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        // Vérifier qu'on ne retire pas plus que le stock disponible
        if ($data['type'] === 'OUT' && $data['quantity'] > $stockItem->quantity) {
            return response()->json([
                'message' => 'Quantité insuffisante en stock'
            ], 422);
        }

        // Créer le mouvement
        $movement = StockMovement::create([
            'user_id' => Auth::id(),
            'company_id' => $company->id,
            'stock_item_id' => $stockItem->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'],
        ]);

        // Mettre à jour la quantité du stock
        if ($data['type'] === 'IN') {
            $stockItem->increment('quantity', $data['quantity']);
        } else {
            $stockItem->decrement('quantity', $data['quantity']);
        }

        return response()->json([
            'message' => 'Mouvement enregistré',
            'movement' => $movement->load('user'),
            'newQuantity' => $stockItem->fresh()->quantity
        ]);
    }

    /**
     * Remove the specified stock item.
     */
    public function destroy(StockItems $stockItem)
    {
        $this->authorizeStockItem($stockItem);

        // Supprimer l'image si elle existe
        if ($stockItem->picture_file_path) {
            Storage::disk('public')->delete($stockItem->picture_file_path);
        }

        $stockItem->delete();

        return response()->json([
            'message' => 'Article supprimé'
        ]);
    }

    /**
     * Sécurité multi-entreprise
     */
    private function authorizeStockItem(StockItems $stockItem): void
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $stockItem->company_id !== $company->id) {
            abort(403, 'Accès interdit');
        }
    }
}
