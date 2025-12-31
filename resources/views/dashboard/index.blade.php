@extends('base')
@section('title', 'Tableau de bord')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('layout.sidebar')
    
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-2 text-sm text-gray-600">Bienvenue, {{ $user->first_name }} {{ $user->last_name }}</p>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total entreprises</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $globalStats['total_companies'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Propriétaire</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $globalStats['owned_companies'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Administrateur</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $globalStats['admin_companies'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Employé</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $globalStats['employee_companies'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par entreprise -->
    <div class="space-y-6">
        @forelse($stats as $stat)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $stat['company']->name }}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $stat['company']->city }} - SIRET: {{ $stat['company']->siret }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($stat['role'] === 'OWNER') bg-blue-100 text-blue-800
                            @elseif($stat['role'] === 'ADMIN') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ $stat['role'] }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Factures totales -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm font-medium text-gray-500">Factures totales</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['total_invoices'] }}</p>
                        </div>

                        <!-- Factures en attente -->
                        <div class="border-l-4 border-yellow-500 pl-4">
                            <p class="text-sm font-medium text-gray-500">En attente</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['pending_invoices'] }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($stat['pending_amount'], 2) }} €</p>
                        </div>

                        <!-- Factures payées -->
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-sm font-medium text-gray-500">Payées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['paid_invoices'] }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($stat['total_revenue'], 2) }} €</p>
                        </div>

                        <!-- Stock -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-sm font-medium text-gray-500">Articles en stock</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['total_stock_items'] }}</p>
                            @if($stat['low_stock_items'] > 0)
                                <p class="text-xs text-red-600">{{ $stat['low_stock_items'] }} en rupture</p>
                            @else
                                <p class="text-xs text-green-600">Stock suffisant</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Voir les détails →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune entreprise</h3>
                <p class="mt-1 text-sm text-gray-500">Vous n'êtes associé à aucune entreprise pour le moment.</p>
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Créer une entreprise
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
</div>
@endsection