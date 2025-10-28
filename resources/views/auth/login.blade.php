<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Boulangerie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        
<style>
        /* Couleurs du pain */
        :root {
            --bread-primary: #D4A574;
            --bread-dark: #8B6F47;
            --bread-light: #F5E6D3;
            --bread-crust: #6B4423;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #F5E6D3 0%, #E8D5C4 100%);">
    @include('partials._sweetalert')
    <!-- Container principal -->
    <div class="w-full max-w-md">
        
        <!-- Card de connexion -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
            
            <!-- Header avec gradient couleur pain -->
            <div class="px-6 py-8 sm:px-8 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #D4A574 0%, #8B6F47 100%);">
                <!-- Motif décoratif -->
                <div class="absolute top-0 right-0 w-32 h-32 opacity-10">
                    <i class="fas fa-bread-slice text-8xl text-white"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="inline-block p-3 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-sign-in-alt text-3xl text-white"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Connexion</h1>
                    <p class="text-white text-opacity-90 mt-2 text-sm sm:text-base">Accédez à votre compte</p>
                </div>
            </div>

            <!-- Corps du formulaire -->
            <div class="px-6 py-8 sm:px-8 sm:py-10">
                
                <!-- Messages d'erreur (si présents) -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg animate-pulse">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                        <div class="flex-1">
                            @foreach($errors->all() as $error)
                            <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf

                    <!-- Champ Numéro de téléphone -->
                    <div class="group">
                        <label for="numero_telephone" class="block text-sm font-semibold mb-2 transition-colors" style="color: #6B4423;">
                            <i class="fas fa-phone mr-2"></i>Numéro de téléphone
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-mobile-alt"></i>
                            </span>
                            <input 
                                type="text" 
                                id="numero_telephone"
                                name="numero_telephone" 
                                value="{{ old('numero_telephone') }}"
                                required 
                                placeholder="6XXXXXXXX"
                                class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-opacity-20 outline-none transition-all duration-300 text-gray-800 placeholder-gray-400 hover:border-gray-300"
                                style="focus:border-color: #D4A574; focus:ring-color: #D4A574;"
                                maxlength="9"
                                pattern="[0-9]{9}"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5 ml-1">Format: 9 chiffres (ex: 612345678)</p>
                    </div>

                    <!-- Champ Code PIN -->
                    <div class="group">
                        <label for="code_pin" class="block text-sm font-semibold mb-2 transition-colors" style="color: #6B4423;">
                            <i class="fas fa-lock mr-2"></i>Code PIN
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-key"></i>
                            </span>
                            <input 
                                type="password" 
                                id="code_pin"
                                name="code_pin" 
                                required
                                placeholder="••••"
                                class="w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-opacity-20 outline-none transition-all duration-300 text-gray-800 placeholder-gray-400 hover:border-gray-300"
                                style="focus:border-color: #D4A574; focus:ring-color: #D4A574;"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                            >
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <button 
                        type="submit" 
                        class="w-full py-4 px-6 rounded-xl font-bold text-white text-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl active:scale-95 focus:outline-none focus:ring-4 focus:ring-opacity-50"
                        style="background: linear-gradient(135deg, #D4A574 0%, #8B6F47 100%); focus:ring-color: #D4A574;"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span>Se connecter</span>
                    </button>
                </form>

                <!-- Lien d'inscription -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-gray-600 text-sm sm:text-base">
                        Pas encore inscrit ?
                        <a 
                            href="{{ route('inscription') }}" 
                            class="font-bold ml-1 transition-all duration-300 hover:underline inline-flex items-center group"
                            style="color: #8B6F47;"
                        >
                            S'inscrire
                            <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </p>
                </div>

                <!-- Mot de passe oublié (optionnel) -->
                <div class="text-center mt-4">
                    <a 
                        href="#" 
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <i class="fas fa-question-circle mr-1"></i>
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer info -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <i class="fas fa-shield-alt mr-1"></i>
            Connexion sécurisée et cryptée
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('code_pin');
            const icon = document.getElementById('toggleIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Format phone number input
        document.getElementById('numero_telephone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Animation au chargement
        window.addEventListener('load', function() {
            document.querySelector('.bg-white').classList.add('animate-fade-in');
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        /* Focus states personnalisés */
        input:focus {
            border-color: #D4A574 !important;
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.1) !important;
        }

        /* Responsive enhancement */
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
        }

        /* Animation du bouton */
        button[type="submit"]:hover {
            box-shadow: 0 20px 25px -5px rgba(139, 111, 71, 0.3), 0 10px 10px -5px rgba(139, 111, 71, 0.2);
        }
    </style>
</body>
</html>