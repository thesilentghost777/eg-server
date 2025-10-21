<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="page-title">Réinitialisation du mot de passe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animation personnalisée */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Focus states améliorés */
        .focus-ring:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
    <!-- Conteneur principal -->
    <div class="w-full max-w-md">
        <!-- Bouton de changement de langue -->
        <div class="flex justify-end mb-6">
            <button 
                id="languageToggle"
                class="bg-white hover:bg-blue-50 text-blue-600 font-medium py-2 px-4 rounded-lg shadow-md transition-all duration-200 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span data-translate="language-toggle">English</span>
                </span>
            </button>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <!-- Icône -->
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2m0 0V7a2 2 0 012-2m6 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V9z"></path>
                    </svg>
                </div>
                
                <!-- Titre -->
                <h1 class="text-2xl font-bold text-gray-800 mb-2" data-translate="title">
                    Réinitialiser votre mot de passe
                </h1>
                
                <!-- Sous-titre -->
                <p class="text-gray-600" data-translate="subtitle">
                    Entrez votre nouveau mot de passe ci-dessous
                </p>
            </div>

            <!-- Formulaire -->
            <form id="resetForm" method="POST" action="/password/store" class="space-y-6">
                <!-- Token CSRF caché -->
                <input type="hidden" name="_token" value="csrf-token-here">
                <input type="hidden" name="token" value="reset-token-here">

                <!-- Champ Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2" data-translate="email-label">
                        Adresse email
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            autofocus 
                            autocomplete="username"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors duration-200 bg-gray-50"
                            placeholder="votre@email.com"
                            data-translate-placeholder="email-placeholder"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-red-600 hidden" id="email-error" data-translate="email-error">
                        Veuillez entrer une adresse email valide
                    </p>
                </div>

                <!-- Champ Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2" data-translate="password-label">
                        Nouveau mot de passe
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors duration-200"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                        >
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Indicateur de force du mot de passe -->
                    <div class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <div id="strength-1" class="h-1 w-full bg-gray-200 rounded"></div>
                            <div id="strength-2" class="h-1 w-full bg-gray-200 rounded"></div>
                            <div id="strength-3" class="h-1 w-full bg-gray-200 rounded"></div>
                            <div id="strength-4" class="h-1 w-full bg-gray-200 rounded"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-gray-500" data-translate="password-strength">
                            Force du mot de passe
                        </p>
                    </div>
                </div>

                <!-- Champ Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2" data-translate="confirm-password-label">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors duration-200"
                            placeholder="••••••••"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg id="matchIcon" class="w-5 h-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-red-600 hidden" id="confirm-error" data-translate="confirm-error">
                        Les mots de passe ne correspondent pas
                    </p>
                </div>

                <!-- Bouton de soumission -->
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg id="submitIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2m0 0V7a2 2 0 012-2m6 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V9z"></path>
                        </svg>
                        <span data-translate="submit-button">Réinitialiser le mot de passe</span>
                    </span>
                </button>
            </form>

            <!-- Lien retour -->
            <div class="text-center mt-6">
                <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200" data-translate="back-to-login">
                    ← Retour à la connexion
                </a>
            </div>
        </div>
    </div>

    <script>
        // Translations
        const translations = {
            fr: {
                'page-title': 'Réinitialisation du mot de passe',
                'language-toggle': 'English',
                'title': 'Réinitialiser votre mot de passe',
                'subtitle': 'Entrez votre nouveau mot de passe ci-dessous',
                'email-label': 'Adresse email',
                'email-placeholder': 'votre@email.com',
                'email-error': 'Veuillez entrer une adresse email valide',
                'password-label': 'Nouveau mot de passe',
                'password-strength': 'Force du mot de passe',
                'confirm-password-label': 'Confirmer le mot de passe',
                'confirm-error': 'Les mots de passe ne correspondent pas',
                'submit-button': 'Réinitialiser le mot de passe',
                'back-to-login': '← Retour à la connexion',
                'strength-weak': 'Faible',
                'strength-fair': 'Moyen',
                'strength-good': 'Bon',
                'strength-strong': 'Fort'
            },
            en: {
                'page-title': 'Password Reset',
                'language-toggle': 'Français',
                'title': 'Reset your password',
                'subtitle': 'Enter your new password below',
                'email-label': 'Email Address',
                'email-placeholder': 'your@email.com',
                'email-error': 'Please enter a valid email address',
                'password-label': 'New Password',
                'password-strength': 'Password strength',
                'confirm-password-label': 'Confirm Password',
                'confirm-error': 'Passwords do not match',
                'submit-button': 'Reset Password',
                'back-to-login': '← Back to login',
                'strength-weak': 'Weak',
                'strength-fair': 'Fair',
                'strength-good': 'Good',
                'strength-strong': 'Strong'
            }
        };

        let currentLang = 'fr';

        // Fonction de traduction
        function translatePage(lang) {
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (translations[lang][key]) {
                    element.textContent = translations[lang][key];
                }
            });

            document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
                const key = element.getAttribute('data-translate-placeholder');
                if (translations[lang][key]) {
                    element.placeholder = translations[lang][key];
                }
            });

            document.documentElement.lang = lang;
            document.title = translations[lang]['page-title'];
        }

        // Toggle de langue
        document.getElementById('languageToggle').addEventListener('click', () => {
            currentLang = currentLang === 'fr' ? 'en' : 'fr';
            translatePage(currentLang);
        });

        // Toggle mot de passe
        document.getElementById('togglePassword').addEventListener('click', () => {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        });

        // Validation et force du mot de passe
        function checkPasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            return Math.min(strength, 4);
        }

        function updatePasswordStrength(strength) {
            const strengthBars = ['strength-1', 'strength-2', 'strength-3', 'strength-4'];
            const strengthText = document.getElementById('strength-text');
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const texts = ['strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
            
            strengthBars.forEach((bar, index) => {
                const element = document.getElementById(bar);
                element.className = 'h-1 w-full rounded';
                
                if (index < strength) {
                    element.classList.add(colors[strength - 1]);
                } else {
                    element.classList.add('bg-gray-200');
                }
            });
            
            if (strength > 0) {
                strengthText.textContent = translations[currentLang][texts[strength - 1]];
                strengthText.className = 'text-xs ' + (strength < 3 ? 'text-red-600' : 'text-green-600');
            } else {
                strengthText.textContent = translations[currentLang]['password-strength'];
                strengthText.className = 'text-xs text-gray-500';
            }
        }

        // Événements de validation
        document.getElementById('password').addEventListener('input', (e) => {
            const strength = checkPasswordStrength(e.target.value);
            updatePasswordStrength(strength);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIcon = document.getElementById('matchIcon');
            const confirmError = document.getElementById('confirm-error');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchIcon.classList.remove('hidden', 'text-red-500');
                    matchIcon.classList.add('text-green-500');
                    confirmError.classList.add('hidden');
                } else {
                    matchIcon.classList.remove('hidden', 'text-green-500');
                    matchIcon.classList.add('text-red-500');
                    confirmError.classList.remove('hidden');
                }
            } else {
                matchIcon.classList.add('hidden');
                confirmError.classList.add('hidden');
            }
        }

        // Validation email
        document.getElementById('email').addEventListener('blur', (e) => {
            const emailError = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (e.target.value && !emailRegex.test(e.target.value)) {
                emailError.classList.remove('hidden');
                e.target.classList.add('border-red-500');
            } else {
                emailError.classList.add('hidden');
                e.target.classList.remove('border-red-500');
            }
        });

        // Soumission du formulaire
        document.getElementById('resetForm').addEventListener('submit', (e) => {
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');
            
            // Animation de chargement
            submitBtn.disabled = true;
            submitIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>';
            submitIcon.classList.add('animate-spin');
            
            // Ici vous pouvez ajouter la logique de soumission réelle
            // Pour la démo, on simule un délai
            setTimeout(() => {
                submitBtn.disabled = false;
                submitIcon.classList.remove('animate-spin');
                submitIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2m0 0V7a2 2 0 012-2m6 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V9z"></path>';
            }, 2000);
        });

        // Animation au focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', (e) => {
                e.target.classList.add('focus-ring');
            });
            
            input.addEventListener('blur', (e) => {
                e.target.classList.remove('focus-ring');
            });
        });
    </script>
</body>
</html>