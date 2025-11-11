<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'EasyGest BP - Gestion Intelligente' : 'EasyGest BP - Smart Management' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-amber-50 via-white to-blue-50">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md shadow-sm z-50" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-bread-slice text-white text-xl md:text-2xl"></i>
                    </div>
                    <span class="text-xl md:text-2xl font-bold bg-gradient-to-r from-amber-600 to-amber-800 bg-clip-text text-transparent">
                        EasyGest BP
                    </span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">
                        {{ $isFrench ? 'Fonctionnalités' : 'Features' }}
                    </a>
                    <a href="#benefits" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">
                        {{ $isFrench ? 'Avantages' : 'Benefits' }}
                    </a>
                    <a href="#workflow" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">
                        {{ $isFrench ? 'Flux de travail' : 'Workflow' }}
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg hover:from-amber-600 hover:to-amber-700 transition-all shadow-md hover:shadow-lg font-semibold">
                        {{ $isFrench ? 'Connexion' : 'Login' }}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'" class="text-2xl text-gray-700"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="md:hidden pb-4">
                <div class="flex flex-col space-y-3">
                    <a href="#features" @click="mobileMenu = false" class="px-4 py-2 text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                        {{ $isFrench ? 'Fonctionnalités' : 'Features' }}
                    </a>
                    <a href="#benefits" @click="mobileMenu = false" class="px-4 py-2 text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                        {{ $isFrench ? 'Avantages' : 'Benefits' }}
                    </a>
                    <a href="#workflow" @click="mobileMenu = false" class="px-4 py-2 text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                        {{ $isFrench ? 'Flux de travail' : 'Workflow' }}
                    </a>
                    <a href="{{ route('login') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg text-center font-semibold">
                        {{ $isFrench ? 'Connexion' : 'Login' }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 md:pt-32 pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-6 md:space-y-8" x-data x-init="$el.classList.add('animate-fade-in')">
                    <div class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold">
                        <i class="fas fa-star mr-2"></i>
                        {{ $isFrench ? 'Solution 100% Hors Ligne' : '100% Offline Solution' }}
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        {{ $isFrench ? 'Gérez votre boulangerie avec' : 'Manage your bakery with' }}
                        <span class="bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-500 bg-clip-text text-transparent">
                            {{ $isFrench ? 'efficacité' : 'efficiency' }}
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                        {{ $isFrench 
                            ? 'Une solution complète pour gérer le flux de produits, les inventaires, les ventes et le personnel de votre boulangerie-pâtisserie. Fonctionne entièrement hors ligne pour une fiabilité maximale.' 
                            : 'A complete solution to manage product flow, inventories, sales and staff of your bakery. Works entirely offline for maximum reliability.' 
                        }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-lg hover:shadow-xl font-semibold text-center text-lg">
                            <i class="fas fa-rocket mr-2"></i>
                            {{ $isFrench ? 'Commencer' : 'Get Started' }}
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white text-amber-600 border-2 border-amber-500 rounded-xl hover:bg-amber-50 transition-all font-semibold text-center text-lg">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ $isFrench ? 'En savoir plus' : 'Learn More' }}
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-amber-600">100%</div>
                            <div class="text-sm text-gray-600">{{ $isFrench ? 'Hors ligne' : 'Offline' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-green-600">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="text-sm text-gray-600">{{ $isFrench ? 'Sécurisé' : 'Secure' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-blue-600">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="text-sm text-gray-600">{{ $isFrench ? 'Multi-appareils' : 'Multi-device' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative lg:pl-12" x-data x-init="$el.classList.add('animate-float')">
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-white to-amber-50 rounded-3xl shadow-2xl p-8 md:p-12 border border-amber-100">
                            <div class="space-y-6">
                                <!-- Dashboard Preview -->
                                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-sm font-semibold opacity-90">{{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}</div>
                                        <div class="flex space-x-2">
                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                            <div class="w-2 h-2 bg-white/70 rounded-full"></div>
                                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold mb-2">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        <span class="counter">2,450</span>
                                    </div>
                                    <div class="text-sm opacity-90">{{ $isFrench ? 'Produits gérés aujourd\'hui' : 'Products managed today' }}</div>
                                </div>

                                <!-- Quick Stats -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                        <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                                        <div class="text-2xl font-bold text-blue-900">3</div>
                                        <div class="text-xs text-blue-700">{{ $isFrench ? 'Vendeurs actifs' : 'Active sellers' }}</div>
                                    </div>
                                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                        <i class="fas fa-box text-green-600 text-2xl mb-2"></i>
                                        <div class="text-2xl font-bold text-green-900">98%</div>
                                        <div class="text-xs text-green-700">{{ $isFrench ? 'Précision' : 'Accuracy' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full opacity-20 blur-2xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-green-400 to-green-500 rounded-full opacity-20 blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="container mx-auto">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-puzzle-piece mr-2"></i>
                    {{ $isFrench ? 'Fonctionnalités' : 'Features' }}
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    {{ $isFrench ? 'Tout ce dont vous avez besoin' : 'Everything you need' }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $isFrench 
                        ? 'Une suite complète d\'outils pour gérer efficacement votre boulangerie-pâtisserie'
                        : 'A complete suite of tools to efficiently manage your bakery'
                    }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature 1 -->
                <div class="group bg-gradient-to-br from-amber-50 to-white p-6 md:p-8 rounded-2xl border-2 border-amber-100 hover:border-amber-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-inbox text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Réception des produits' : 'Product Reception' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Les pointeurs enregistrent les réceptions et les affectent automatiquement aux vendeurs actifs'
                            : 'Pointers record receptions and automatically assign them to active sellers'
                        }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-gradient-to-br from-blue-50 to-white p-6 md:p-8 rounded-2xl border-2 border-blue-100 hover:border-blue-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Inventaire intelligent' : 'Smart Inventory' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Gestion fluide des changements de vendeurs avec double validation par code PIN'
                            : 'Smooth seller changes management with double PIN validation'
                        }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-gradient-to-br from-green-50 to-white p-6 md:p-8 rounded-2xl border-2 border-green-100 hover:border-green-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-cash-register text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Sessions de vente' : 'Sales Sessions' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Suivi des ventes avec gestion des paiements cash, Orange Money et MTN Money'
                            : 'Sales tracking with cash, Orange Money and MTN Money management'
                        }}
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="group bg-gradient-to-br from-red-50 to-white p-6 md:p-8 rounded-2xl border-2 border-red-100 hover:border-red-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-undo text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Retours de produits' : 'Product Returns' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Enregistrement des retours (produits gâtés, périmés) avec traçabilité complète'
                            : 'Returns recording (spoiled, expired products) with complete traceability'
                        }}
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="group bg-gradient-to-br from-purple-50 to-white p-6 md:p-8 rounded-2xl border-2 border-purple-100 hover:border-purple-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Flux de produits' : 'Product Flow' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Vue détaillée du flux: entrées, sorties, retours et calcul automatique des ventes'
                            : 'Detailed flow view: inputs, outputs, returns and automatic sales calculation'
                        }}
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="group bg-gradient-to-br from-indigo-50 to-white p-6 md:p-8 rounded-2xl border-2 border-indigo-100 hover:border-indigo-300 transition-all hover:shadow-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-users-cog text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $isFrench ? 'Gestion du personnel' : 'Staff Management' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'CRUD complet sur les utilisateurs avec activation/désactivation et gestion des rôles'
                            : 'Complete user CRUD with activation/deactivation and role management'
                        }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <div>
                        <div class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold mb-4">
                            <i class="fas fa-thumbs-up mr-2"></i>
                            {{ $isFrench ? 'Avantages' : 'Benefits' }}
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                            {{ $isFrench ? 'Pourquoi choisir EasyGest BP ?' : 'Why choose EasyGest BP?' }}
                        </h2>
                        <p class="text-lg text-gray-600">
                            {{ $isFrench 
                                ? 'Une solution pensée pour les défis quotidiens des boulangeries-pâtisseries'
                                : 'A solution designed for the daily challenges of bakeries'
                            }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-wifi-slash text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">
                                    {{ $isFrench ? 'Fonctionne hors ligne' : 'Works offline' }}
                                </h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $isFrench 
                                        ? 'Aucune dépendance Internet. Travaillez sans interruption, même en cas de panne réseau'
                                        : 'No Internet dependency. Work without interruption, even during network outages'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lock text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">
                                    {{ $isFrench ? 'Sécurité renforcée' : 'Enhanced security' }}
                                </h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $isFrench 
                                        ? 'Validation par code PIN, verrouillage logique et contrôle d\'accès par rôle'
                                        : 'PIN validation, logical locking and role-based access control'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-sync text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">
                                    {{ $isFrench ? 'Traçabilité complète' : 'Complete traceability' }}
                                </h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $isFrench 
                                        ? 'Suivez chaque mouvement de produit, de la réception à la vente finale'
                                        : 'Track every product movement from reception to final sale'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-mobile-alt text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">
                                    {{ $isFrench ? 'Multi-appareils' : 'Multi-device' }}
                                </h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $isFrench 
                                        ? 'Tablettes pour vendeurs et pointeurs, PC pour le PDG. Responsive sur tous les écrans'
                                        : 'Tablets for sellers and pointers, PC for CEO. Responsive on all screens'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Stats Cards -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-2xl text-white shadow-xl col-span-2">
                        <i class="fas fa-chart-line text-4xl mb-4 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">+85%</div>
                        <div class="text-amber-100">
                            {{ $isFrench ? 'Efficacité opérationnelle' : 'Operational efficiency' }}
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg border-2 border-blue-100">
                        <i class="fas fa-clock text-3xl text-blue-600 mb-4"></i>
                        <div class="text-3xl font-bold text-gray-900 mb-2">-60%</div>
                        <div class="text-sm text-gray-600">
                            {{ $isFrench ? 'Temps de gestion' : 'Management time' }}
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg border-2 border-green-100">
                        <i class="fas fa-check-circle text-3xl text-green-600 mb-4"></i>
                        <div class="text-3xl font-bold text-gray-900 mb-2">99%</div>
                        <div class="text-sm text-gray-600">
                            {{ $isFrench ? 'Précision' : 'Accuracy' }}
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="workflow" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="container mx-auto">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-block px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-project-diagram mr-2"></i>
                    {{ $isFrench ? 'Flux de travail' : 'Workflow' }}
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    {{ $isFrench ? 'Comment ça fonctionne ?' : 'How does it work?' }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $isFrench 
                        ? 'Un flux de travail simple et efficace pour gérer votre boulangerie au quotidien'
                        : 'A simple and efficient workflow to manage your bakery daily'
                    }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-amber-50 to-white p-6 rounded-2xl border-2 border-amber-200 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4 text-white font-bold text-xl">
                            1
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $isFrench ? 'Réception' : 'Reception' }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $isFrench 
                                ? 'Le pointeur enregistre les produits reçus et les affecte aux vendeurs'
                                : 'Pointer records received products and assigns them to sellers'
                            }}
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-gradient-to-r from-amber-300 to-blue-300"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl border-2 border-blue-200 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4 text-white font-bold text-xl">
                            2
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $isFrench ? 'Vente' : 'Sale' }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $isFrench 
                                ? 'Les vendeurs gèrent les sessions de vente avec différents modes de paiement'
                                : 'Sellers manage sales sessions with different payment methods'
                            }}
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-gradient-to-r from-blue-300 to-green-300"></div>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-2xl border-2 border-green-200 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4 text-white font-bold text-xl">
                            3
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $isFrench ? 'Retours' : 'Returns' }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $isFrench 
                                ? 'Enregistrement des produits invendus ou gâtés pour traçabilité'
                                : 'Recording unsold or spoiled products for traceability'
                            }}
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-gradient-to-r from-green-300 to-purple-300"></div>
                </div>

                <!-- Step 4 -->
                <div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-2xl border-2 border-purple-200 hover:shadow-xl transition-shadow">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 text-white font-bold text-xl">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Analyse' : 'Analysis' }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        {{ $isFrench 
                            ? 'Le PDG consulte les rapports et flux de produits détaillés'
                            : 'CEO reviews reports and detailed product flows'
                        }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-amber-500 via-amber-600 to-yellow-500">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                {{ $isFrench ? 'Prêt à transformer votre boulangerie ?' : 'Ready to transform your bakery?' }}
            </h2>
            <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                {{ $isFrench 
                    ? 'Rejoignez-nous et découvrez une gestion simple, efficace et fiable'
                    : 'Join us and discover simple, efficient and reliable management'
                }}
            </p>
            <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-white text-amber-600 rounded-xl hover:bg-gray-100 transition-all shadow-xl hover:shadow-2xl font-bold text-lg">
                <i class="fas fa-rocket mr-2"></i>
                {{ $isFrench ? 'Commencer maintenant' : 'Start now' }}
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bread-slice text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold">EasyGest BP</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        {{ $isFrench 
                            ? 'Solution de gestion intelligente pour boulangeries-pâtisseries'
                            : 'Smart management solution for bakeries'
                        }}
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ $isFrench ? 'Liens rapides' : 'Quick Links' }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="text-gray-400 hover:text-amber-400 transition-colors">{{ $isFrench ? 'Fonctionnalités' : 'Features' }}</a></li>
                        <li><a href="#benefits" class="text-gray-400 hover:text-amber-400 transition-colors">{{ $isFrench ? 'Avantages' : 'Benefits' }}</a></li>
                        <li><a href="#workflow" class="text-gray-400 hover:text-amber-400 transition-colors">{{ $isFrench ? 'Flux de travail' : 'Workflow' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>techforgesolution237.supahuman.site</li>
                        <li><i class="fas fa-phone mr-2"></i>+237 657929578</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} EasyGest BP. {{ $isFrench ? 'Tous droits réservés.' : 'All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

</body>
</html>