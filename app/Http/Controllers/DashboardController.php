<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les entreprises de l'utilisateur avec leurs statistiques
        $companies = $user->companies()
            ->wherePivot('is_active', true)
            ->get();
        
        $stats = [];
        
        foreach ($companies as $company) {
            // Statistiques par entreprise
            $stats[] = [
                'company' => $company,
                'role' => $company->pivot->role,
                'total_invoices' => $company->invoices()->count(),
                'pending_invoices' => $company->invoices()->where('status', 'pending')->count(),
                'paid_invoices' => $company->invoices()->where('status', 'paid')->count(),
                'total_revenue' => $company->invoices()->where('status', 'paid')->sum('total_amount'),
                'pending_amount' => $company->invoices()->where('status', 'pending')->sum('total_amount'),
                'total_stock_items' => $company->stockItems()->count(),
                'low_stock_items' => $company->stockItems()->where('quantity', '<', 10)->count(),
            ];
        }
        
        // Statistiques globales
        $globalStats = [
            'total_companies' => $companies->count(),
            'owned_companies' => $companies->where('pivot.role', 'owner')->count(),
            'admin_companies' => $companies->where('pivot.role', 'admin')->count(),
            'employee_companies' => $companies->where('pivot.role', 'employee')->count(),
        ];
        
        return view('dashboard.index', compact('stats', 'globalStats', 'user'));
    }

    public function patient()
    {
        return view('dashboard.patient');
    }
    
    public function vehicle()
    {
        return view('dashboard.vehicle');
    }

}
