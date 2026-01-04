@extends('base')
@section('title', 'Tableau de bord')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('layout.sidebar')
    
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
        <div>
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tableau de bord</h1>
                    <p class="mt-2 text-gray-500">Bienvenue, <span class="font-semibold text-gray-700">{{ $user->first_name }}</span>. Voici l'état de l'entreprise <span class="font-semibold text-indigo-600">{{ $company->name ?? 'N/A' }}</span> aujourd'hui.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        {{ now()->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                    <a href="{{ route('dashboard.transport') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Nouveau transport
                    </a>
                </div>
            </div>

            @if($company)
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Courses du jour -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Courses du jour</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['today_transports'] }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>

                    <!-- CA Mensuel -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">CA mensuel (encaissé)</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['monthly_revenue'], 2) }} €</h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    <!-- Factures en attente -->
                    <a href="{{ route('dashboard.invoice.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between hover:shadow-md transition-shadow cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">En attente ({{ $stats['pending_invoices_count'] }})</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_invoices_amount'], 2) }} €</h3>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded-lg text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </a>

                    <!-- Flotte active -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Véhicules actifs</p>
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['active_vehicles'] }}</h3>
                                <span class="text-sm text-gray-500">/ {{ $stats['total_vehicles'] }}</span>
                            </div>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Main Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column (Charts) -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Activity Chart -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Activité de la semaine</h3>
                            <div class="relative h-72">
                                <canvas id="activityChart"></canvas>
                            </div>
                        </div>

                        <!-- Upcoming Transports -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-900">Prochains transports</h3>
                                <a href="{{ route('dashboard.transport') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50 text-xs uppercase font-medium text-gray-500">
                                        <tr>
                                            <th class="px-6 py-3">Heure</th>
                                            <th class="px-6 py-3">Type</th>
                                            <th class="px-6 py-3">Patient</th>
                                            <th class="px-6 py-3">Destination</th>
                                            <th class="px-6 py-3">Chauffeur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($upcomingTransports as $transport)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($transport->transport_date)->isToday() ? 'Auj.' : \Carbon\Carbon::parse($transport->transport_date)->format('d/m') }}
                                                    <span class="text-indigo-600 font-bold ml-1">{{ substr($transport->start_time, 0, 5) }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $transport->transport_type === 'AMBULANCE' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ ucfirst(strtolower($transport->transport_type)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 font-medium">{{ $transport->patient->first_name ?? 'Inconnu' }} {{ $transport->patient->last_name ?? '' }}</td>
                                                <td class="px-6 py-4 truncate max-w-xs" title="{{ $transport->destination_address }}">{{ $transport->destination_address }}</td>
                                                <td class="px-6 py-4">
                                                    @if($transport->driver)
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600">
                                                                {{ substr($transport->driver->first_name, 0, 1) }}{{ substr($transport->driver->last_name, 0, 1) }}
                                                            </div>
                                                            <span class="truncate">{{ $transport->driver->first_name }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic">Non assigné</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                                    Aucun transport prévu prochainement.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Shortcuts & Secondary Info) -->
                    <div class="space-y-8">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Accès rapide</h3>
                            <div class="space-y-3">
                                <a href="{{ route('dashboard.patient') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100 group">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Nouveau patient</div>
                                        <div class="text-xs text-gray-500">Créer une fiche patient</div>
                                    </div>
                                </a>
                                <a href="{{ route('dashboard.invoice.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100 group">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Nouvelle facture</div>
                                        <div class="text-xs text-gray-500">Gérer les factures</div>
                                    </div>
                                </a>
                                <a href="{{ route('dashboard.stock') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100 group">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Vérifier le stock</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $stats['low_stock'] > 0 ? $stats['low_stock'] . ' alertes' : 'Tout est OK' }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Transport Types Chart -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Répartition (Mois)</h3>
                            <div class="relative h-48">
                                <canvas id="typesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucune entreprise active</h2>
                    <p class="text-gray-500 max-w-md mx-auto mb-8">Pour commencer à utiliser le tableau de bord, vous devez être associé à une entreprise.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($company)
<script>
    // Activity Chart
    const ctxActivity = document.getElementById('activityChart').getContext('2d');
    new Chart(ctxActivity, {
        type: 'line',
        data: {
            labels: @json($charts['activity_labels']),
            datasets: [{
                label: 'Transports',
                data: @json($charts['activity_data']),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#f3f4f6' },
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Types Chart
    const ctxTypes = document.getElementById('typesChart').getContext('2d');
    new Chart(ctxTypes, {
        type: 'doughnut',
        data: {
            labels: @json($charts['types_labels']),
            datasets: [{
                data: @json($charts['types_data']),
                backgroundColor: ['#3b82f6', '#ef4444', '#eab308', '#a855f7'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 6 } }
            }
        }
    });
</script>
@endif
@endsection