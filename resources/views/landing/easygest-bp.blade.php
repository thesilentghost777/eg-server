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
    </style>
</head>
<body class="bg-bread-deeper text-white overflow-x-hidden">

    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[100] flex items-center justify-center hero-gradient transition-opacity duration-500">
        <div class="text-center">
            <div class="relative w-32 h-32 mx-auto mb-6">
                <div class="absolute inset-0 border-4 border-bread-gold/30 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-bread-gold rounded-full animate-spin"></div>
                <div class="absolute inset-4 flex items-center justify-center steam">
                    <span class="text-4xl">🥐</span>
                </div>
            </div>
            <p class="text-bread-gold font-semibold text-xl">EasyGest BP</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="#" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-bread-accent to-bread-gold rounded-xl flex items-center justify-center shadow-lg pulse-gold">
                        <span class="text-2xl">🥐</span>
                    </div>
                    <p class="text-xl font-bold">EasyGest BP</p>
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#accueil" class="text-white/80 hover:text-bread-gold transition-colors">Accueil</a>
                    <a href="#fonctionnalites" class="text-white/80 hover:text-bread-gold transition-colors">Fonctionnalités</a>
                    <a href="#roles" class="text-white/80 hover:text-bread-gold transition-colors">Rôles</a>
                    <a href="#flux" class="text-white/80 hover:text-bread-gold transition-colors">Flux</a>
                </div>
                <a href="https://play.google.com/store" target="_blank" class="bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-6 py-3 rounded-full font-semibold hover:shadow-lg hover:shadow-bread-gold/30 transition-all">
                    Télécharger
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="min-h-screen hero-gradient bread-pattern relative flex items-center overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-bread-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-bread-accent/10 rounded-full blur-3xl"></div>
        
        <!-- Floating bread icons -->
        <div class="absolute top-1/4 left-1/5 text-4xl opacity-20 float-bread" style="animation-delay: 0s;">🍞</div>
        <div class="absolute top-1/3 right-1/4 text-5xl opacity-20 float-bread" style="animation-delay: 1.5s;">🥖</div>
        <div class="absolute bottom-1/3 left-1/3 text-3xl opacity-20 float-bread" style="animation-delay: 3s;">🥐</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full mb-6 offline-badge">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-sm">Fonctionne 100% Hors-ligne</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                        Gérez votre<br>
                        <span class="gradient-text">Boulangerie</span>
                    </h1>
                    <p class="text-xl text-white/70 mb-8 leading-relaxed">
                        La solution complète de gestion pour boulangeries et pâtisseries. 
                        Suivi des flux, inventaires et sessions de vente - même sans internet!
                    </p>
                    <div class="flex flex-wrap gap-4 mb-10">
                        <a href="https://play.google.com/store" target="_blank" class="group bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-bread-gold/40 transition-all flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                            Télécharger l'app
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-center">
                            <p class="text-3xl font-bold gradient-text">3</p>
                            <p class="text-white/60 text-sm">Rôles utilisateurs</p>
                        </div>
                        <div class="w-px h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-3xl font-bold gradient-text">2</p>
                            <p class="text-white/60 text-sm">Catégories</p>
                        </div>
                        <div class="w-px h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-3xl font-bold gradient-text">100%</p>
                            <p class="text-white/60 text-sm">Offline</p>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left" class="relative">
                    <div class="float-bread">
                        <div class="glass rounded-3xl p-8 relative">
                            <div class="absolute -top-6 -right-6 w-20 h-20 bg-gradient-to-br from-bread-accent to-bread-gold rounded-2xl flex items-center justify-center text-4xl shadow-xl pulse-gold steam">
                                🥐
                            </div>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-xl">
                                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                                        <span class="text-blue-400 text-xl">👆</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Pointeur</p>
                                        <p class="text-white/60 text-sm">Réceptions & Retours</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-xl">
                                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                                        <span class="text-green-400 text-xl">🛒</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Vendeur</p>
                                        <p class="text-white/60 text-sm">Sessions & Inventaires</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-bread-gold/10 rounded-xl border border-bread-gold/30">
                                    <div class="w-12 h-12 bg-bread-gold/20 rounded-full flex items-center justify-center">
                                        <span class="text-bread-gold text-xl">👑</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-bread-gold">PDG</p>
                                        <p class="text-white/60 text-sm">Contrôle Total</p>
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
    <section id="fonctionnalites" class="py-24 bg-gradient-to-b from-bread-deeper to-bread-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold">Fonctionnalités</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-3">Gestion complète & intuitive</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📶
                    </div>
                    <h3 class="text-xl font-bold mb-3">Mode Offline</h3>
                    <p class="text-white/70">Fonctionne sans connexion internet. Synchronisation automatique dès que le réseau est disponible.</p>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📦
                    </div>
                    <h3 class="text-xl font-bold mb-3">Réceptions</h3>
                    <p class="text-white/70">Enregistrez les produits reçus des producteurs. Attribution automatique au vendeur actif de la catégorie.</p>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        ↩️
                    </div>
                    <h3 class="text-xl font-bold mb-3">Retours</h3>
                    <p class="text-white/70">Gérez les retours de produits (périmés, abîmés, etc.). Traçabilité complète par vendeur.</p>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📋
                    </div>
                    <h3 class="text-xl font-bold mb-3">Inventaires</h3>
                    <p class="text-white/70">Passation de service entre vendeurs avec validation PIN. Changement automatique de vendeur actif.</p>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-gradient-to-br from-bread-accent to-bread-gold rounded-2xl flex items-center justify-center text-3xl mb-6">
                        💰
                    </div>
                    <h3 class="text-xl font-bold mb-3">Sessions de Vente</h3>
                    <p class="text-white/70">Ouvrez des sessions avec fond de caisse et Mobile Money. Fermeture par le PDG avec calcul du manquant.</p>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📊
                    </div>
                    <h3 class="text-xl font-bold mb-3">Flux de Produits</h3>
                    <p class="text-white/70">Visualisez entrées, sorties, retours et ventes. Calcul automatique des quantités vendues.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section id="roles" class="py-24 hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold">Équipe</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-3">3 Rôles, 1 Solution</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Pointeur -->
                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        👆
                    </div>
                    <h3 class="text-2xl font-bold text-center mb-4">Pointeur</h3>
                    <p class="text-white/60 text-center mb-6">L'homme de confiance qui gère les flux</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Réceptionner les produits</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Enregistrer les retours</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Modifier les réceptions</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Consulter l'historique</span>
                        </li>
                    </ul>
                </div>

                <!-- Vendeur -->
                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        🛒
                    </div>
                    <h3 class="text-2xl font-bold text-center mb-4">Vendeur</h3>
                    <p class="text-white/60 text-center mb-6">Boulangerie ou Pâtisserie</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Ouvrir une session de vente</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Faire l'inventaire (passation)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Consulter les réceptions</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-green-400">✓</span>
                            <span>Voir son flux produits</span>
                        </li>
                    </ul>
                </div>

                <!-- PDG -->
                <div class="glass rounded-2xl p-8 card-hover border-2 border-bread-gold/30" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 bg-gradient-to-br from-bread-accent to-bread-gold rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        👑
                    </div>
                    <h3 class="text-2xl font-bold text-center mb-4 gradient-text">PDG</h3>
                    <p class="text-white/60 text-center mb-6">Contrôle total de l'entreprise</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="text-bread-gold">★</span>
                            <span>Visualiser tous les flux</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-bread-gold">★</span>
                            <span>Modifier toutes les données</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-bread-gold">★</span>
                            <span>Fermer les sessions</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-bread-gold">★</span>
                            <span>Gérer utilisateurs & produits</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Flow Section -->
    <section id="flux" class="py-24 bg-bread-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold">Flux de Produits</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-3">Traçabilité complète</h2>
            </div>

            <div class="glass rounded-3xl p-8 max-w-4xl mx-auto" data-aos="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-white/10">
                                <th class="pb-4 text-bread-gold">Produit</th>
                                <th class="pb-4 text-center">Trouvé</th>
                                <th class="pb-4 text-center">Reçu</th>
                                <th class="pb-4 text-center">Retourné</th>
                                <th class="pb-4 text-center">Restant</th>
                                <th class="pb-4 text-center text-green-400">Vendu</th>
                            </tr>
                        </thead>
                        <tbody class="text-white/80">
                            <tr class="border-b border-white/5">
                                <td class="py-4">🥐 Croissant</td>
                                <td class="py-4 text-center">10</td>
                                <td class="py-4 text-center text-blue-400">+50</td>
                                <td class="py-4 text-center text-red-400">-5</td>
                                <td class="py-4 text-center">8</td>
                                <td class="py-4 text-center text-green-400 font-bold">47</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-4">🍞 Pain Complet</td>
                                <td class="py-4 text-center">5</td>
                                <td class="py-4 text-center text-blue-400">+30</td>
                                <td class="py-4 text-center text-red-400">-2</td>
                                <td class="py-4 text-center">3</td>
                                <td class="py-4 text-center text-green-400 font-bold">30</td>
                            </tr>
                            <tr>
                                <td class="py-4">🎂 Gâteau Choco</td>
                                <td class="py-4 text-center">2</td>
                                <td class="py-4 text-center text-blue-400">+15</td>
                                <td class="py-4 text-center text-red-400">-1</td>
                                <td class="py-4 text-center">4</td>
                                <td class="py-4 text-center text-green-400 font-bold">12</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-center text-white/50 mt-6 text-sm">
                    Formule: <span class="text-bread-gold">Vendu = Trouvé + Reçu - Retourné - Restant</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-24 hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-bread-gold font-semibold">Catégories</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-3">Boulangerie & Pâtisserie</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-6xl text-center mb-6">🥖</div>
                    <h3 class="text-2xl font-bold text-center mb-4">Boulangerie</h3>
                    <p class="text-white/70 text-center mb-6">Pains, baguettes, viennoiseries et produits de boulangerie traditionnelle.</p>
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-1 bg-bread-gold/20 rounded-full text-sm">🍞 Pain</span>
                        <span class="px-3 py-1 bg-bread-gold/20 rounded-full text-sm">🥐 Croissant</span>
                        <span class="px-3 py-1 bg-bread-gold/20 rounded-full text-sm">🥖 Baguette</span>
                    </div>
                </div>

                <div class="glass rounded-2xl p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-6xl text-center mb-6">🎂</div>
                    <h3 class="text-2xl font-bold text-center mb-4">Pâtisserie</h3>
                    <p class="text-white/70 text-center mb-6">Gâteaux, tartes, éclairs et créations sucrées artisanales.</p>
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-1 bg-pink-500/20 rounded-full text-sm">🍰 Gâteau</span>
                        <span class="px-3 py-1 bg-pink-500/20 rounded-full text-sm">🧁 Cupcake</span>
                        <span class="px-3 py-1 bg-pink-500/20 rounded-full text-sm">🍩 Donut</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-bread-dark relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-bread-gold/5 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Optimisez votre boulangerie!</h2>
            <p class="text-xl text-white/70 mb-10">Rejoignez les professionnels qui utilisent EasyGest BP pour une gestion sans faille.</p>
            
            <a href="https://play.google.com/store" target="_blank" class="inline-flex items-center gap-3 bg-gradient-to-r from-bread-accent to-bread-gold text-bread-dark px-10 py-5 rounded-full font-bold text-xl hover:shadow-2xl hover:shadow-bread-gold/40 transition-all pulse-gold">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                Télécharger EasyGest BP
            </a>

            <div class="mt-12 flex flex-wrap justify-center gap-8">
                <a href="https://wa.me/237696087354" class="glass rounded-2xl p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-2xl">📱</span>
                    <p class="font-semibold mt-2">+237 696 087 354</p>
                </a>
                <a href="mailto:tsf237@gmail.com" class="glass rounded-2xl p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-2xl">✉️</span>
                    <p class="font-semibold mt-2">tsf237@gmail.com</p>
                </a>
                <a href="https://techforgesolution237.site" target="_blank" class="glass rounded-2xl p-6 hover:bg-bread-gold/10 transition-colors">
                    <span class="text-2xl">🌐</span>
                    <p class="font-semibold mt-2">techforgesolution237.site</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-bread-deeper border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-bread-accent to-bread-gold rounded-xl flex items-center justify-center">
                    <span class="text-xl">🥐</span>
                </div>
                <span class="font-bold text-xl">EasyGest BP</span>
            </div>
            <p class="text-white/60">© 2026 EasyGest BP. Powered By <a href="https://techforgesolution237.site" target="_blank" class="text-bread-gold hover:underline">TFS237</a></p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        window.addEventListener('load', () => { 
            document.getElementById('preloader').style.opacity = '0'; 
            setTimeout(() => document.getElementById('preloader').style.display = 'none', 500); 
        });
        AOS.init({ duration: 800, once: true, offset: 100 });
        window.addEventListener('scroll', () => { 
            document.getElementById('navbar').classList.toggle('bg-bread-dark/95', window.pageYOffset > 100); 
            document.getElementById('navbar').classList.toggle('backdrop-blur-xl', window.pageYOffset > 100); 
        });
    </script>
</body>
</html>
