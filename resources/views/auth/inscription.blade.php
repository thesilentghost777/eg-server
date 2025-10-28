<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Boulangerie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5e6d3 0%, #e8d4b8 50%, #d4b896 100%);
            min-height: 100vh;
        }
        
        .bread-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(139, 69, 19, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(160, 82, 45, 0.03) 0%, transparent 50%);
        }
        
        .card-gradient {
            background: linear-gradient(145deg, #ffffff 0%, #fefdfb 100%);
            box-shadow: 
                0 20px 60px rgba(139, 69, 19, 0.15),
                0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(184, 134, 11, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #d4a574 0%, #b8860b 50%, #8b6914 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(139, 69, 19, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
        }
        
        .wheat-icon {
            color: #d4a574;
            animation: sway 3s ease-in-out infinite;
        }
        
        @keyframes sway {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .label-text {
            color: #5d4037;
            font-weight: 500;
        }
        
        select option {
            padding: 10px;
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="bread-pattern">
    <div class="min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl fade-in">
            <!-- Header avec logo -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-bread-slice text-5xl sm:text-6xl wheat-icon"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-amber-900 mb-2">Boulangerie Française</h1>
                <p class="text-amber-700 text-sm sm:text-base">EasyGest BP</p>
            </div>

            <!-- Card principale -->
            <div class="card-gradient rounded-2xl sm:rounded-3xl overflow-hidden">
                <!-- Header de la card -->
                <div class="bg-gradient-to-r from-amber-700 to-amber-700 px-6 sm:px-8 py-6 sm:py-8">
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-user-plus text-2xl sm:text-3xl text-white"></i>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Inscription</h2>
                    </div>
                    <p class="text-center text-amber-100 mt-2 text-sm sm:text-base">Créez votre compte personnel</p>
                </div>

                <!-- Corps de la card -->
                <div class="px-6 sm:px-8 md:px-10 py-8 sm:py-10">
                    <form id="inscriptionForm" method="POST" action="{{ route('inscription.post') }}" class="space-y-5 sm:space-y-6">
                        @csrf
                        
                        <!-- Nom complet -->
                        <div class="space-y-2">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-user text-amber-700"></i>
                                <span>Nom complet</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="Ex: Jean Dupont"
                            >
                        </div>

                        <!-- Numéro de téléphone -->
                        <div class="space-y-2">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-phone text-amber-700"></i>
                                <span>Numéro de téléphone</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="numero_telephone" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50" 
                                value="{{ old('numero_telephone') }}" 
                                required
                                placeholder="657929578"
                            >
                            
                        </div>

                        <!-- Rôle -->
                        <div class="space-y-2">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-briefcase text-amber-700"></i>
                                <span>Rôle</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="role" 
                                id="role" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50 cursor-pointer" 
                                required
                            >
                                <option value="">Sélectionner un rôle</option>
                                <option value="pdg">🏢 PDG</option>
                                <option value="pointeur">📊 Pointeur</option>
                                <option value="vendeur_boulangerie">🥖 Vendeur Boulangerie</option>
                                <option value="vendeur_patisserie">🍰 Vendeur Pâtisserie</option>
                                <option value="producteur">👨‍🍳 Producteur</option>
                            </select>
                        </div>

                        <!-- Code PDG (conditionnel) -->
                        <div id="codePdgDiv" class="space-y-2 hidden">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-key text-amber-700"></i>
                                <span>Code PDG</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="code_pdg" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50" 
                                placeholder="Code d'autorisation PDG"
                            >
                            <p class="text-xs sm:text-sm text-amber-600 flex items-center space-x-1">
                                <i class="fas fa-shield-alt"></i>
                                <span>Code requis uniquement pour le rôle PDG</span>
                            </p>
                        </div>

                        <!-- Code PIN -->
                        <div class="space-y-2">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-lock text-amber-700"></i>
                                <span>Code PIN</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="code_pin" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50" 
                                required
                                minlength="4"
                                placeholder="Minimum 4 caractères"
                            >
                            <p class="text-xs sm:text-sm text-amber-600 flex items-center space-x-1">
                                <i class="fas fa-info-circle"></i>
                                <span>Au moins 4 caractères pour votre sécurité</span>
                            </p>
                        </div>

                        <!-- Langue préférée -->
                        <div class="space-y-2">
                            <label class="label-text text-sm sm:text-base flex items-center space-x-2">
                                <i class="fas fa-language text-amber-700"></i>
                                <span>Langue préférée</span>
                            </label>
                            <select 
                                name="preferred_language" 
                                class="input-focus w-full px-4 py-3 sm:py-4 border-2 border-amber-200 rounded-xl sm:rounded-2xl focus:border-amber-500 focus:outline-none text-sm sm:text-base bg-amber-50 cursor-pointer"
                            >
                                <option value="fr" selected>🇫🇷 Français</option>
                                <option value="en">🇬🇧 English</option>
                            </select>
                        </div>

                        <!-- Bouton de soumission -->
                        <button 
                            type="submit" 
                            class="btn-primary w-full py-4 sm:py-5 rounded-xl sm:rounded-2xl text-white font-bold text-base sm:text-lg shadow-lg hover:shadow-2xl flex items-center justify-center space-x-3"
                        >
                            <i class="fas fa-user-plus text-xl"></i>
                            <span>S'inscrire maintenant</span>
                        </button>
                    </form>

                    <!-- Lien de connexion -->
                    <div class="mt-6 sm:mt-8 text-center">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-amber-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs sm:text-sm">
                                <span class="px-4 bg-white text-amber-700 font-medium">Déjà membre ?</span>
                            </div>
                        </div>
                        <a 
                            href="{{ route('login') }}" 
                            class="inline-block mt-4 text-amber-700 hover:text-amber-900 font-semibold text-sm sm:text-base transition-all hover:underline decoration-2 underline-offset-4"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter à mon compte
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 sm:mt-8 text-amber-800 text-xs sm:text-sm">
                <p class="flex items-center justify-center space-x-2">
                    <i class="fas fa-shield-alt"></i>
                    <span>Vos données sont sécurisées et confidentielles</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Gestion de l'affichage conditionnel du code PDG
        document.getElementById('role').addEventListener('change', function() {
            const codePdgDiv = document.getElementById('codePdgDiv');
            const codePdgInput = codePdgDiv.querySelector('input');
            
            if (this.value === 'pdg') {
                codePdgDiv.classList.remove('hidden');
                codePdgDiv.classList.add('fade-in');
                codePdgInput.setAttribute('required', 'required');
            } else {
                codePdgDiv.classList.add('hidden');
                codePdgInput.removeAttribute('required');
                codePdgInput.value = '';
            }
        });

        // Animation au focus des inputs
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-102');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-102');
            });
        });

        // Validation du formulaire
        document.getElementById('inscriptionForm').addEventListener('submit', function(e) {
            const phone = document.querySelector('input[name="numero_telephone"]').value;
            const pin = document.querySelector('input[name="code_pin"]').value;
            
            // Validation du numéro de téléphone
            if (!/^(6\d{8}|2376\d{8})$/.test(phone)) {
                e.preventDefault();
                alert('⚠️ Format de téléphone invalide. Utilisez 6XXXXXXXX ou 2376XXXXXXXX');
                return false;
            }
            
            // Validation du code PIN
            if (pin.length < 4) {
                e.preventDefault();
                alert('⚠️ Le code PIN doit contenir au moins 4 caractères');
                return false;
            }
        });
    </script>
</body>
</html>