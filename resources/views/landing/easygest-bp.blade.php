<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EasyGest BP - Gestion complète de boulangerie-pâtisserie en mode offline">
    <title>EasyGest BP | Gestion Boulangerie-Pâtisserie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bread: {
                            primary: '#92400e',
                            secondary: '#b45309',
                            accent: '#d97706',
                            gold: '#fbbf24',
                            cream: '#fef3c7',
                            dark: '#451a03',
                            deeper: '#2a1001'
                        }
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Poppins', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 50%, #b45309 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #451a03 100%);
        }
        
        .bread-pattern {
            background-image: radial-gradient(circle at 20% 80%, rgba(251, 191, 36, 0.1) 0%, transparent 50%),
                              radial-gradient(circle at 80% 20%, rgba(217, 119, 6, 0.1) 0%, transparent 50%);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(251, 191, 36, 0.3);
        }
        
        .pulse-gold {
            animation: pulseGold 2s ease-in-out infinite;
        }
        
        @keyframes pulseGold {
            0%, 100% { box-shadow: 0 0 20px rgba(251, 191, 36, 0.4); }
            50% { box-shadow: 0 0 40px rgba(251, 191, 36, 0.8); }
        }
        
        .float-bread {
            animation: floatBread 5s ease-in-out infinite;
        }
        
        @keyframes floatBread {
            0%, 100% { transform: translateY(0) rotate(-3deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
        }
        
        .steam {
            position: relative;
        }
        
        .steam::before {
            content: '~';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            opacity: 0.5;
            animation: steamRise 2s ease-out infinite;
        }
        
        @keyframes steamRise {
            0% { opacity: 0.5; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        
        .offline-badge {
            animation: offlinePulse 3s ease-in-out infinite;
        }
        
        @keyframes offlinePulse {
            0%, 100% { background-color: rgba(34, 197, 94, 0.2); }
            50% { background-color: rgba(34, 197, 94, 0.4); }
        }

        /* Menu Mobile */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.active {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-bread-deeper text-white overflow-x-hidden">

    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[100] flex items-center justify-center hero-gradient transition-opacity duration-500">
        <div class="text-center">
            <div class="relative w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-6">
                <div class="absolute inset-0 border-4 border-bread-gold/30 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-bread-gold rounded-full animate-spin"></div>
                <div class="absolute inset-4 flex items-center justify-center steam">
                    <span class="text-3xl sm:text-4xl">🥐</span>
                </div>
            </div>
            <p class="text-bread-gold font-semibold text-lg sm:text-xl">EasyGest BP</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <a href="#" class="flex items-center gap-2 sm:gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-bread-accent to-bread-gold rounded-xl flex items-center justify-center shadow-lg pulse-gold">
                        <span class="text-xl sm:text-2xl">🥐</span>
                    </div>
                    <p class="text-lg sm:text-xl font-bold">EasyGest BP</p>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#accueil" class="text-white/80 hover:text-bread-gold transition-colors">Accueil</a>
                    <a href="#fonctionnalites" class="text-white/80 hover:text-bread-gold transition-colors">Fonctionnalités</a>
                    <a href="#roles" class="text-white/80 hover:text-bread-gold transition-colors">Rôles</a>
                    <a href="#flux" class="text-white/80 hover:text-bread-gold transition-colors">Flux</a>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="https://play.google.com/store" target="_blank" class="hidden sm:block bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-4 sm:px-6 py-2 sm:py-3 rounded-full font-semibold hover:shadow-lg hover:shadow-bread-gold/30 transition-all text-sm sm:text-base">
                        Télécharger
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button id="menuBtn" class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5">
                        <span class="w-6 h-0.5 bg-white transition-all"></span>
                        <span class="w-6 h-0.5 bg-white transition-all"></span>
                        <span class="w-6 h-0.5 bg-white transition-all"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 w-full sm:w-80 h-full bg-bread-dark z-[60] md:hidden">
        <div class="flex flex-col h-full p-6">
            <button id="closeMenu" class="self-end text-3xl mb-8">&times;</button>
            <div class="flex flex-col gap-6">
                <a href="#accueil" class="text-xl text-white/80 hover:text-bread-gold transition-colors">Accueil</a>
                <a href="#fonctionnalites" class="text-xl text-white/80 hover:text-bread-gold transition-colors">Fonctionnalités</a>
                <a href="#roles" class="text-xl text-white/80 hover:text-bread-gold transition-colors">Rôles</a>
                <a href="#flux" class="text-xl text-white/80 hover:text-bread-gold transition-colors">Flux</a>
                <a href="https://play.google.com/store" target="_blank" class="mt-4 bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-6 py-3 rounded-full font-semibold text-center">
                    Télécharger
                </a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="accueil" class="min-h-screen hero-gradient bread-pattern relative flex items-center overflow-hidden pt-20 sm:pt-0">
        <!-- Decorative elements -->
        <div class="absolute top-20 right-10 w-48 h-48 sm:w-72 sm:h-72 bg-bread-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-64 h-64 sm:w-96 sm:h-96 bg-bread-accent/10 rounded-full blur-3xl"></div>
        
        <!-- Floating bread icons - Hidden on small mobile -->
        <div class="hidden sm:block absolute top-1/4 left-1/5 text-4xl opacity-20 float-bread">🍞</div>
        <div class="hidden sm:block absolute top-1/3 right-1/4 text-5xl opacity-20 float-bread" style="animation-delay: 1.5s;">🥖</div>
        <div class="hidden sm:block absolute bottom-1/3 left-1/3 text-3xl opacity-20 float-bread" style="animation-delay: 3s;">🥐</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-32 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 glass px-3 py-2 sm:px-4 sm:py-2 rounded-full mb-4 sm:mb-6 offline-badge text-sm sm:text-base">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span>Fonctionne 100% Hors-ligne</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-4 sm:mb-6 leading-tight">
                        Gérez votre<br>
                        <span class="gradient-text">Boulangerie</span>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl text-white/70 mb-6 sm:mb-8 leading-relaxed">
                        La solution complète de gestion pour boulangeries et pâtisseries. 
                        Suivi des flux, inventaires et sessions de vente - même sans internet!
                    </p>
                    <div class="flex flex-wrap gap-3 sm:gap-4 mb-6 sm:mb-10">
                        <a href="https://play.google.com/store" target="_blank" class="group bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-bread-gold/40 transition-all flex items-center gap-2 sm:gap-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                            <span class="hidden sm:inline">Télécharger l'app</span>
                            <span class="sm:hidden">Télécharger</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4 sm:gap-8 justify-center sm:justify-start">
                        <div class="text-center">
                            <p class="text-2xl sm:text-3xl font-bold gradient-text">3</p>
                            <p class="text-white/60 text-xs sm:text-sm">Rôles utilisateurs</p>
                        </div>
                        <div class="w-px h-10 sm:h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-2xl sm:text-3xl font-bold gradient-text">2</p>
                            <p class="text-white/60 text-xs sm:text-sm">Catégories</p>
                        </div>
                        <div class="w-px h-10 sm:h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-2xl sm:text-3xl font-bold gradient-text">100%</p>
                            <p class="text-white/60 text-xs sm:text-sm">Offline</p>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left" class="relative mt-8 lg:mt-0">
                    <div class="float-bread">
                        <div class="glass rounded-3xl p-6 sm:p-8 relative">
                            <div class="absolute -top-4 -right-4 sm:-top-6 sm:-right-6 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-bread-accent to-bread-gold rounded-2xl flex items-center justify-center text-3xl sm:text-4xl shadow-xl pulse-gold steam">
                                🥐
                            </div>
                            <div class="space-y-4 sm:space-y-6">
                                <div class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-white/5 rounded-xl">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-400 text-lg sm:text-xl">👆</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm sm:text-base">Pointeur</p>
                                        <p class="text-white/60 text-xs sm:text-sm">Réceptions & Retours</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-white/5 rounded-xl">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-green-400 text-lg sm:text-xl">🛒</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm sm:text-base">Vendeur</p>
                                        <p class="text-white/60 text-xs sm:text-sm">Sessions & Inventaires</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-bread-gold/10 rounded-xl border border-bread-gold/30">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-bread-gold/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-bread-gold text-lg sm:text-xl">👑</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-bread-gold text-sm sm:text-base">PDG</p>
                                        <p class="text-white/60 text-xs sm:text-sm">Contrôle Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fonctionnalites" class="py-16 sm:py-24 bg-gradient-to-b from-bread-deeper to-bread-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold text-sm sm:text-base">Fonctionnalités</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3">Gestion complète & intuitive</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        📶
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Mode Offline</h3>
                    <p class="text-white/70 text-sm sm:text-base">Fonctionne sans connexion internet. Synchronisation automatique dès que le réseau est disponible.</p>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        📦
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Réceptions</h3>
                    <p class="text-white/70 text-sm sm:text-base">Enregistrez les produits reçus des producteurs. Attribution automatique au vendeur actif de la catégorie.</p>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        ↩️
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Retours</h3>
                    <p class="text-white/70 text-sm sm:text-base">Gérez les retours de produits (périmés, abîmés, etc.). Traçabilité complète par vendeur.</p>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        📋
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Inventaires</h3>
                    <p class="text-white/70 text-sm sm:text-base">Passation de service entre vendeurs avec validation PIN. Changement automatique de vendeur actif.</p>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-bread-accent to-bread-gold rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        💰
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Sessions de Vente</h3>
                    <p class="text-white/70 text-sm sm:text-base">Ouvrez des sessions avec fond de caisse et Mobile Money. Fermeture par le PDG avec calcul du manquant.</p>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-4 sm:mb-6">
                        📊
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3">Flux de Produits</h3>
                    <p class="text-white/70 text-sm sm:text-base">Visualisez entrées, sorties, retours et ventes. Calcul automatique des quantités vendues.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section id="roles" class="py-16 sm:py-24 hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold text-sm sm:text-base">Équipe</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3">3 Rôles, 1 Solution</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Pointeur -->
                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-3xl sm:text-4xl mx-auto mb-4 sm:mb-6">
                        👆
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">Pointeur</h3>
                    <p class="text-white/60 text-center mb-4 sm:mb-6 text-sm sm:text-base">L'homme de confiance qui gère les flux</p>
                    <ul class="space-y-2 sm:space-y-3 text-sm sm:text-base">
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Réceptionner les produits</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Enregistrer les retours</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Modifier les réceptions</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Consulter l'historique</span>
                        </li>
                    </ul>
                </div>

                <!-- Vendeur -->
                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-3xl sm:text-4xl mx-auto mb-4 sm:mb-6">
                        🛒
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">Vendeur</h3>
                    <p class="text-white/60 text-center mb-4 sm:mb-6 text-sm sm:text-base">Boulangerie ou Pâtisserie</p>
                    <ul class="space-y-2 sm:space-y-3 text-sm sm:text-base">
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Ouvrir une session de vente</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Faire l'inventaire (passation)</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Consulter les réceptions</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-green-400 flex-shrink-0">✓</span>
                            <span>Voir son flux produits</span>
                        </li>
                    </ul>
                </div>

                <!-- PDG -->
                <div class="glass rounded-2xl p-6 sm:p-8 card-hover border-2 border-bread-gold/30 sm:col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-bread-accent to-bread-gold rounded-full flex items-center justify-center text-3xl sm:text-4xl mx-auto mb-4 sm:mb-6">
                        👑
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4 gradient-text">PDG</h3>
                    <p class="text-white/60 text-center mb-4 sm:mb-6 text-sm sm:text-base">Contrôle total de l'entreprise</p>
                    <ul class="space-y-2 sm:space-y-3 text-sm sm:text-base">
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-bread-gold flex-shrink-0">★</span>
                            <span>Visualiser tous les flux</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-bread-gold flex-shrink-0">★</span>
                            <span>Modifier toutes les données</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-bread-gold flex-shrink-0">★</span>
                            <span>Fermer les sessions</span>
                        </li>
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="text-bread-gold flex-shrink-0">★</span>
                            <span>Gérer utilisateurs & produits</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Flow Section -->
    <section id="flux" class="py-16 sm:py-24 bg-bread-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold text-sm sm:text-base">Flux de Produits</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3">Traçabilité complète</h2>
            </div>

            <div class="glass rounded-3xl p-4 sm:p-8 max-w-4xl mx-auto" data-aos="fade-up">
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle px-4 sm:px-0">
                        <table class="w-full text-sm sm:text-base">
                            <thead>
                                <tr class="text-left border-b border-white/10">
                                    <th class="pb-3 sm:pb-4 text-bread-gold pr-2">Produit</th>
                                    <th class="pb-3 sm:pb-4 text-center px-1 sm:px-2">Trouvé</th>
                                    <th class="pb-3 sm:pb-4 text-center px-1 sm:px-2">Reçu</th>
                                    <th class="pb-3 sm:pb-4 text-center px-1 sm:px-2">Retour</th>
                                    <th class="pb-3 sm:pb-4 text-center px-1 sm:px-2">Restant</th>
                                    <th class="pb-3 sm:pb-4 text-center text-green-400 pl-1 sm:pl-2">Vendu</th>
                                </tr>
                            </thead>
                            <tbody class="text-white/80">
                                <tr class="border-b border-white/5">
                                    <td class="py-3 sm:py-4 pr-2">
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <span class="hidden sm:inline">🥐</span>
                                            <span class="text-xs sm:text-base">Croissant</span>
                                        </div>
                                    </td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">10</td>
                                    <td class="py-3 sm:py-4 text-center text-blue-400 px-1 sm:px-2">+50</td>
                                    <td class="py-3 sm:py-4 text-center text-red-400 px-1 sm:px-2">-5</td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">8</td>
                                    <td class="py-3 sm:py-4 text-center text-green-400 font-bold pl-1 sm:pl-2">47</td>
                                </tr>
                                <tr class="border-b border-white/5">
                                    <td class="py-3 sm:py-4 pr-2">
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <span class="hidden sm:inline">🍞</span>
                                            <span class="text-xs sm:text-base">Pain Complet</span>
                                        </div>
                                    </td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">5</td>
                                    <td class="py-3 sm:py-4 text-center text-blue-400 px-1 sm:px-2">+30</td>
                                    <td class="py-3 sm:py-4 text-center text-red-400 px-1 sm:px-2">-2</td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">3</td>
                                    <td class="py-3 sm:py-4 text-center text-green-400 font-bold pl-1 sm:pl-2">30</td>
                                </tr>
                                <tr>
                                    <td class="py-3 sm:py-4 pr-2">
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <span class="hidden sm:inline">🎂</span>
                                            <span class="text-xs sm:text-base">Gâteau Choco</span>
                                        </div>
                                    </td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">2</td>
                                    <td class="py-3 sm:py-4 text-center text-blue-400 px-1 sm:px-2">+15</td>
                                    <td class="py-3 sm:py-4 text-center text-red-400 px-1 sm:px-2">-1</td>
                                    <td class="py-3 sm:py-4 text-center px-1 sm:px-2">4</td>
                                    <td class="py-3 sm:py-4 text-center text-green-400 font-bold pl-1 sm:pl-2">12</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="text-center text-white/50 mt-4 sm:mt-6 text-xs sm:text-sm px-2">
                    Formule: <span class="text-bread-gold">Vendu = Trouvé + Reçu - Retourné - Restant</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 sm:py-24 hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold text-sm sm:text-base">Catégories</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3">Boulangerie & Pâtisserie</h2>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-5xl sm:text-6xl text-center mb-4 sm:mb-6">🥖</div>
                    <h3 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">Boulangerie</h3>
                    <p class="text-white/70 text-center mb-4 sm:mb-6 text-sm sm:text-base">Pains, baguettes, viennoiseries et produits de boulangerie traditionnelle.</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="px-2 sm:px-3 py-1 bg-bread-gold/20 rounded-full text-xs sm:text-sm">🍞 Pain</span>
                        <span class="px-2 sm:px-3 py-1 bg-bread-gold/20 rounded-full text-xs sm:text-sm">🥐 Croissant</span>
                        <span class="px-2 sm:px-3 py-1 bg-bread-gold/20 rounded-full text-xs sm:text-sm">🥖 Baguette</span>
                    </div>
                </div>

                <div class="glass rounded-2xl p-6 sm:p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-5xl sm:text-6xl text-center mb-4 sm:mb-6">🎂</div>
                    <h3 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">Pâtisserie</h3>
                    <p class="text-white/70 text-center mb-4 sm:mb-6 text-sm sm:text-base">Gâteaux, tartes, éclairs et créations sucrées artisanales.</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="px-2 sm:px-3 py-1 bg-pink-500/20 rounded-full text-xs sm:text-sm">🍰 Gâteau</span>
                        <span class="px-2 sm:px-3 py-1 bg-pink-500/20 rounded-full text-xs sm:text-sm">🧁 Cupcake</span>
                        <span class="px-2 sm:px-3 py-1 bg-pink-500/20 rounded-full text-xs sm:text-sm">🍩 Donut</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-24 bg-bread-dark relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] sm:w-[800px] h-[600px] sm:h-[800px] bg-bread-gold/5 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">Optimisez votre boulangerie!</h2>
            <p class="text-base sm:text-lg md:text-xl text-white/70 mb-8 sm:mb-10 px-4">Rejoignez les professionnels qui utilisent EasyGest BP pour une gestion sans faille.</p>
            
            <a href="https://play.google.com/store" target="_blank" class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-6 sm:px-10 py-4 sm:py-5 rounded-full font-bold text-base sm:text-lg md:text-xl hover:shadow-2xl hover:shadow-bread-gold/40 transition-all pulse-gold">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                <span class="hidden sm:inline">Télécharger EasyGest BP</span>
                <span class="sm:hidden">Télécharger</span>
            </a>

            <div class="mt-8 sm:mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-8">
                <a href="https://wa.me/237696087354" class="glass rounded-2xl p-4 sm:p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-xl sm:text-2xl">📱</span>
                    <p class="font-semibold mt-2 text-sm sm:text-base">+237 696 087 354</p>
                </a>
                <a href="mailto:tsf237@gmail.com" class="glass rounded-2xl p-4 sm:p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-xl sm:text-2xl">✉️</span>
                    <p class="font-semibold mt-2 text-sm sm:text-base break-all">tsf237@gmail.com</p>
                </a>
                <a href="https://techforgesolution237.site" target="_blank" class="glass rounded-2xl p-4 sm:p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-xl sm:text-2xl">🌐</span>
                    <p class="font-semibold mt-2 text-sm sm:text-base break-all">techforgesolution237.site</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-6 sm:py-8 bg-bread-deeper border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-bread-accent to-bread-gold rounded-xl flex items-center justify-center">
                    <span class="text-lg sm:text-xl">🥐</span>
                </div>
                <span class="font-bold text-lg sm:text-xl">EasyGest BP</span>
            </div>
            <p class="text-white/60 text-sm sm:text-base">© 2026 EasyGest BP. Powered By <a href="https://techforgesolution237.site" target="_blank" class="text-bread-gold hover:underline">TFS237</a></p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Preloader
        window.addEventListener('load', () => { 
            document.getElementById('preloader').style.opacity = '0'; 
            setTimeout(() => document.getElementById('preloader').style.display = 'none', 500); 
        });

        // AOS Animation
        AOS.init({ duration: 800, once: true, offset: 50 });

        // Navbar scroll effect
        window.addEventListener('scroll', () => { 
            const navbar = document.getElementById('navbar');
            if (window.pageYOffset > 100) {
                navbar.classList.add('bg-bread-dark/95', 'backdrop-blur-xl', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-bread-dark/95', 'backdrop-blur-xl', 'shadow-lg');
            }
        });

        // Mobile menu
        const menuBtn = document.getElementById('menuBtn');
        const closeMenu = document.getElementById('closeMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuLinks = mobileMenu.querySelectorAll('a');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });

        // Close menu when clicking on a link
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        });
    </script>
</body>
</html>