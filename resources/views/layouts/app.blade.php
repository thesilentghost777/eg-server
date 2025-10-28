<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Splash Screen Styles */
            #splash-screen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #D4A574 0%, #8B6F47 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                opacity: 1;
                transition: opacity 0.8s ease-out;
            }

            #splash-screen.hide {
                opacity: 0;
                pointer-events: none;
            }

            .splash-logo-container {
                position: relative;
                width: 25vw;
                height: 25vw;
                max-width: 300px;
                max-height: 300px;
                min-width: 120px;
                min-height: 120px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .splash-logo {
                font-family: 'Figtree', sans-serif;
                width: 60%;
                height: 60%;
                background: linear-gradient(135deg, #E6B97D 0%, #C8935F 100%);
                border-radius: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: calc(5vw + 20px);
                font-weight: bold;
                color: #FFFFFF;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                z-index: 2;
                position: relative;
                animation: logo-pulse 3s ease-in-out infinite;
            }

            .hostinger-ring {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 4px solid transparent;
                border-radius: 50%;
                animation: hostinger-spin 2s linear infinite;
            }

            .hostinger-ring:before {
                content: '';
                position: absolute;
                top: -4px;
                left: -4px;
                width: 100%;
                height: 100%;
                border: 4px solid transparent;
                border-top: 4px solid rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                animation: hostinger-spin 2s linear infinite;
            }

            .hostinger-ring:after {
                content: '';
                position: absolute;
                top: -8px;
                left: -8px;
                width: calc(100% + 16px);
                height: calc(100% + 16px);
                border: 2px solid transparent;
                border-top: 2px solid rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                animation: hostinger-spin 3s linear infinite reverse;
            }

            @keyframes hostinger-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes logo-pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }

            .splash-text {
                position: absolute;
                bottom: 20%;
                left: 50%;
                transform: translateX(-50%);
                color: white;
                font-family: 'Figtree', sans-serif;
                font-size: calc(1vw + 16px);
                font-weight: 600;
                opacity: 0.9;
                letter-spacing: 1px;
            }

            .splash-particles {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            .particle {
                position: absolute;
                width: 4px;
                height: 4px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
                50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
            }

            @media (max-width: 768px) {
                .splash-logo-container {
                    width: 40vw;
                    height: 40vw;
                    max-width: 200px;
                    max-height: 200px;
                }
                
                .splash-logo {
                    font-size: calc(8vw + 10px);
                    border-radius: 15px;
                }
                
                .splash-text {
                    font-size: 18px;
                    bottom: 25%;
                }
            }

            /* Fix pour les menus déroulants - z-index plus élevé */
            .nav-item.group:hover > ul,
            .nav-item.group > ul {
                z-index: 9999 !important;
            }

            /* Assurer que la navigation a un z-index approprié */
            nav {
                z-index: 1000 !important;
                position: relative;
            }

            /* Les dropdowns doivent être au-dessus de tout le contenu */
            .group-hover\:opacity-100 {
                z-index: 9999 !important;
            }
        </style>
       
        @PwaHead
    </head>
    <body class="font-sans antialiased">
        <!-- Splash Screen -->
        <div id="splash-screen">
            <div class="splash-particles">
                @for ($i = 1; $i <= 9; $i++)
                    <div class="particle" style="left: {{ $i * 10 }}%; animation-delay: {{ ($i - 1) * 0.5 }}s;"></div>
                @endfor
            </div>
            
            <div class="splash-logo-container">
                <div class="hostinger-ring"></div>
                <div class="splash-logo">EG</div>
            </div>
            
            <div class="splash-text">
                {{ $isFrench ?? true ? 'chargement...' : 'loading...' }}
            </div>
        </div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow" style="position: relative; z-index: 10;">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main style="position: relative; z-index: 1;">
                <!-- Navigation moderne et épurée -->
                <nav class="bg-gradient-to-r from-amber-900 to-amber-900 shadow-xl sticky top-0" style="z-index: 1000;">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <ul class="flex items-center space-x-1 py-0">
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a class="flex items-center px-4 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-all duration-200 rounded-lg font-medium text-sm" 
                                   href="{{ route('pdg.dashboard') }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Dashboard
                                </a>
                            </li>

                            <!-- Produits -->
                            <li class="nav-item group relative" style="z-index: 1001;">
                                <a class="flex items-center px-4 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-all duration-200 rounded-lg font-medium text-sm cursor-pointer" 
                                   href="{{ route('produits.index') }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Produits
                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </a>
                                <ul class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-2 border border-gray-100" style="z-index: 9999;">
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-t-lg" 
                                           href="{{ route('produits.index') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                            Liste des Produits
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-b-lg" 
                                           href="{{ route('produits.create') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Créer un Produit
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Sessions de Vente -->
                            <li class="nav-item group relative" style="z-index: 1001;">
                                <a class="flex items-center px-4 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-all duration-200 rounded-lg font-medium text-sm cursor-pointer" 
                                   href="{{ route('sessions.index') }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Sessions
                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </a>
                                <ul class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-2 border border-gray-100" style="z-index: 9999;">
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-t-lg" 
                                           href="{{ route('sessions.index') }}">
                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Liste des Sessions
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-b-lg" 
                                           href="{{ route('sessions.historique') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Historique
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Menu PDG -->
                            @if(auth()->check() && auth()->user()->role === 'pdg')
                            <li class="nav-item group relative" style="z-index: 1001;">
                                <a class="flex items-center px-4 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-all duration-200 rounded-lg font-medium text-sm cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    PDG
                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </a>
                                <ul class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-2 border border-gray-100" style="z-index: 9999;">
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-t-lg" 
                                           href="{{ route('pdg.dashboard') }}">
                                            <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Dashboard PDG
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.receptions') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            Réceptions
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.inventaires') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            Inventaires
                                        </a>
                                    </li>
                                    <li><hr class="my-2 border-gray-200"></li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.sessions') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Sessions Vente
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.sessions.detaillees') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                            Sessions Détaillées
                                        </a>
                                    </li>
                                    <li><hr class="my-2 border-gray-200"></li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.flux.form') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            Flux Opérationnel
                                        </a>
                                    </li>
                                    <li><hr class="my-2 border-gray-200"></li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('pdg.statistiques') }}">
                                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Statistiques
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-b-lg" 
                                           href="{{ route('pdg.vendeurs.performance') }}">
                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            Performance Vendeurs
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Gestion des Utilisateurs (PDG) -->
                            <li class="nav-item group relative" style="z-index: 1001;">
                                <a class="flex items-center px-4 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-all duration-200 rounded-lg font-medium text-sm cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Utilisateurs
                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </a>
                                <ul class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-2 border border-gray-100" style="z-index: 9999;">
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150 rounded-t-lg" 
                                           href="{{ route('users.index') }}">
                                            <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                            Liste des Utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150" 
                                           href="{{ route('users.create') }}">
                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                            </svg>
                                            Créer un Utilisateur
                                        </a>
                                    </li>
                                    <li><hr class="my-2 border-gray-200"></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </nav>
                @yield('content')
            </main>
        </div>

        <script>
            // Gestion optimisée du splash screen
            const splashScreen = document.getElementById('splash-screen');
            const MAX_SPLASH_DURATION = 3000;
            let splashHidden = false;

            function hideSplash() {
                if (!splashHidden) {
                    splashHidden = true;
                    splashScreen.classList.add('hide');
                }
            }

            // Affiche instantanément au chargement
            document.addEventListener('DOMContentLoaded', () => {
                splashScreen.classList.remove('hide');
                setTimeout(hideSplash, MAX_SPLASH_DURATION);
            });

            // Masque après chargement complet
            window.addEventListener('load', () => {
                setTimeout(hideSplash, 100);
            });

            // Gestion de la navigation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (link && !link.getAttribute('href').startsWith('#') && !link.hasAttribute('target')) {
                    splashHidden = false;
                    splashScreen.classList.remove('hide');
                    setTimeout(hideSplash, MAX_SPLASH_DURATION);
                }
            });

            // Gestion des dropdowns au survol (desktop)
            if (window.innerWidth >= 768) {
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.addEventListener('mouseenter', () => {
                        dropdown.querySelector('.dropdown-menu').classList.remove('hidden');
                    });
                    dropdown.addEventListener('mouseleave', () => {
                        dropdown.querySelector('.dropdown-menu').classList.add('hidden');
                    });
                });
            }

            // Gestion des dropdowns au clic (mobile)
            if (window.innerWidth < 768) {
                document.querySelectorAll('.dropdown > .nav-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const menu = link.nextElementSibling;
                        menu.classList.toggle('hidden');
                    });
                });
            }
        </script>
        @include('partials._sweetalert')
        @RegisterServiceWorkerScript
    </body>
</html>