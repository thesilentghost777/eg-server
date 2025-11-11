@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Logo animé en coin -->
        <div class="fixed top-20 right-4 md:top-24 md:right-8 z-50 animate-spin-slow">
            <div class="bg-white rounded-full shadow-2xl p-3 md:p-4 border-4 border-amber-600">
                <img src="{{ asset('logo_officiel.png') }}" alt="EasyGest BP Logo" class="w-16 h-16 md:w-20 md:h-20 object-contain">
            </div>
        </div>

        <!-- En-tête du Dashboard -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-amber-900 mb-4 font-['Figtree']">
                {{ $isFrench ? 'Bienvenue sur EasyGest BP' : 'Welcome to EasyGest BP' }}
            </h1>
            <p class="text-lg md:text-xl text-amber-700 font-medium">
                {{ $isFrench ? 'Système de Gestion pour Boulangerie & Pâtisserie' : 'Bakery & Pastry Management System' }}
            </p>
        </div>

        <!-- Verset Biblique -->
        <div class="mb-12 max-w-4xl mx-auto">
            <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-amber-700 rounded-2xl shadow-2xl p-8 md:p-10 transform hover:scale-105 transition-transform duration-300">
                <div class="text-center">
                    <i class="fas fa-bible text-white text-4xl md:text-5xl mb-6"></i>
                    <blockquote class="text-white text-xl md:text-2xl lg:text-3xl font-semibold italic leading-relaxed mb-4">
                        {{ $isFrench ? '"Remets ton sort à l\'Éternel, et il te soutiendra, Il ne laissera jamais chanceler le juste."' : '"Commit your way to the LORD; trust in Him, and He will act."' }}
                    </blockquote>
                    <cite class="text-amber-100 text-lg md:text-xl font-medium">
                        {{ $isFrench ? '— Psaume 55:22' : '— Psalm 55:22' }}
                    </cite>
                </div>
            </div>
        </div>

        <!-- Fonctionnalités Principales -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-amber-900 text-center mb-8 font-['Figtree']">
                {{ $isFrench ? 'Fonctionnalités Principales' : 'Main Features' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Gestion des Produits -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-amber-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-bread-slice text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Gestion des Produits' : 'Product Management' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Créez et gérez vos produits de boulangerie et pâtisserie avec leurs prix et catégories.' : 'Create and manage your bakery and pastry products with prices and categories.' }}
                    </p>
                </div>
            </div>

            <!-- Flux de Pointage -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-orange-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-dolly text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Flux de Pointage' : 'Product Flow Tracking' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Les pointeurs réceptionnent et assignent automatiquement les produits aux vendeurs actifs.' : 'Pointers receive and automatically assign products to active sellers.' }}
                    </p>
                </div>
            </div>

            <!-- Gestion des Retours -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-red-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-red-500 to-pink-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-undo-alt text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Gestion des Retours' : 'Returns Management' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Enregistrez les retours de produits (gâtés, périmés) liés automatiquement au vendeur.' : 'Record product returns (spoiled, expired) automatically linked to the seller.' }}
                    </p>
                </div>
            </div>

            <!-- Inventaire -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-green-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-clipboard-list text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Inventaire' : 'Inventory' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Validation des quantités lors des changements de vendeurs avec double authentification PIN.' : 'Quantity validation during seller changes with dual PIN authentication.' }}
                    </p>
                </div>
            </div>

            <!-- Sessions de Vente -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-blue-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-cash-register text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Sessions de Vente' : 'Sales Sessions' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Gérez les journées de vente avec fonds de caisse, Orange Money et MTN Money.' : 'Manage sales days with cash funds, Orange Money and MTN Money.' }}
                    </p>
                </div>
            </div>

            <!-- Tableau de Bord PDG -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-purple-600 transform hover:-translate-y-2">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mb-6 mx-auto">
                        <i class="fas fa-chart-line text-white text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-amber-900 text-center mb-4">
                        {{ $isFrench ? 'Tableau de Bord PDG' : 'CEO Dashboard' }}
                    </h3>
                    <p class="text-gray-700 text-center leading-relaxed">
                        {{ $isFrench ? 'Visualisez les flux, fermez les sessions, gérez les utilisateurs et corrigez les erreurs.' : 'View flows, close sessions, manage users and correct errors.' }}
                    </p>
                </div>
            </div>

        </div>

        

        <!-- Footer avec mode offline -->
        <div class="mt-16 text-center">
            <div class="inline-flex items-center bg-white rounded-full px-6 py-3 shadow-lg border-2 border-amber-600">
                <i class="fas fa-wifi-slash text-amber-600 text-xl mr-3"></i>
                <span class="text-amber-900 font-semibold">
                    {{ $isFrench ? 'Fonctionne en mode hors ligne' : 'Works in offline mode' }}
                </span>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }

    /* Police Figtree appliquée */
    body {
        font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
    }

    /* Améliorations de performance */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Optimisation pour les petits écrans */
    @media (max-width: 640px) {
        h1 {
            font-size: 2rem;
        }
        h2 {
            font-size: 1.75rem;
        }
    }
</style>
@endsection