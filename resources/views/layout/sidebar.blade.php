@php
    $currentCompany = Auth::user()->companies->first();
@endphp
<!-- Sidebar Component -->
<div class="flex flex-col w-64 bg-white border-r border-gray-200 h-screen shadow-sm">
    <!-- Logo -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="text-xl font-bold text-gray-900">AmbuGaz</span>
        </div>
    </div>

    <!-- Workspace selector -->
    <div class="px-4 py-4 border-b border-gray-200">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Espace de travail</div>
        <button
            class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 border border-gray-200">
            <div class="flex items-center space-x-2">
                <div
                    class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                    <span class="text-xs font-bold text-white">
                        {{ strtoupper(substr($currentCompany->name ?? 'N/A', 0, 1)) }}
                    </span>
                </div>
                <span class="truncate">
                    {{ $currentCompany->name ?? 'Entreprise' }}
                </span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-4">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">Menu</div>
        <div class="space-y-1">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Tableau de bord
            </a>


            <!-- Gestion Salariés -->
            <a href="{{ route('dashboard.user') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 {{ request()->routeIs('dashboard.user') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-500 transition-colors {{ request()->routeIs('dashboard.user') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Gestion salariés
            </a>

            <!-- Désinfection -->
            <a href="{{ route('dashboard.disinfection') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.disinfection') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.disinfection') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-green-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Désinfection
            </a>

            <!-- Factures -->
            <a href="#"
                class="group flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Factures
            </a>

            <!-- Patients -->
            <a href="{{ route('dashboard.patient') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.patient') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.patient') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Patients
            </a>

            <!-- Véhicules -->
            <a href="{{ route('dashboard.vehicle') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.vehicle') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.vehicle') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                Véhicules
            </a>

            <!-- Stock -->
            <a href="{{ route('dashboard.stock') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.stock') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.stock') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Stock
            </a>

            <!-- Mes documents -->
            <a href="{{ route('dashboard.my-documents') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.my-documents') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.my-documents') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-orange-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Mes documents
            </a>

            <!-- Documents entreprise (admin/owner only) -->
            @php
                $userCompany = Auth::user()->companies->first();
                $userRole = $userCompany ? strtolower($userCompany->pivot->role) : null;
            @endphp
            @if(in_array($userRole, ['admin', 'owner']))
                <a href="{{ route('dashboard.documents') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.documents') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.documents') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-teal-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Documents entreprise
                </a>
            @endif

            <!-- Courses / Transports -->
            <a href="#"
                class="group flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                Courses / Transports
            </a>

            <!-- Documentation -->
            <a href="{{ route('dashboard.documentation') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200 {{ request()->routeIs('dashboard.documentation') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-200 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors duration-200 {{ request()->routeIs('dashboard.documentation') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-blue-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Documentation
            </a>

            <!-- Divider -->
            <div class="h-px bg-gray-200 my-3"></div>

            <!-- Administration -->
            <a href="#"
                class="group flex items-center justify-between px-3 py-2.5 text-sm font-medium bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Administration
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

        </div>
    </nav>

    <!-- Footer -->
    <div class="border-t border-gray-200 bg-gray-50">
        <!-- User profile -->
        <div class="px-4 py-3">
            <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white transition-all duration-200">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md">
                        <span
                            class="text-sm font-bold text-white">{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        @php
                            $currentCompany = Auth::user()->companies->first();
                            $role = $currentCompany ? strtolower($currentCompany->pivot->role) : null;
                        @endphp
                        @if($role === 'owner')
                            Chef d'entreprise
                        @elseif($role === 'admin')
                            Administrateur
                        @elseif($role === 'employee')
                            Salarié
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Logout button -->
        <form method="POST" action="{{ route('auth.logout') }}" class="px-4 pb-3">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-3 py-2.5 text-sm font-medium text-red-600 hover:text-white bg-white hover:bg-red-600 rounded-lg border border-red-200 hover:border-red-600 transition-all duration-200 group shadow-sm">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</div>