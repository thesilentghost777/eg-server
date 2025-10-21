<!DOCTYPE html>
<html lang="fr" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Mot de passe oublié</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Appliquer la police Inter à toute la page */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Style pour le bouton de langue actif */
        .lang-btn.active {
            background-color: #EBF5FF; /* Light blue */
            color: #2563EB; /* Blue */
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-slate-50">

    <div class="absolute top-4 right-4 z-10">
        <div class="flex items-center bg-white border border-slate-200 rounded-full shadow-sm">
            <button onclick="switchLanguage('fr')" id="lang-fr" class="lang-btn px-4 py-1.5 text-sm text-slate-600 rounded-full transition-colors duration-300">
                FR
            </button>
            <button onclick="switchLanguage('en')" id="lang-en" class="lang-btn px-4 py-1.5 text-sm text-slate-600 rounded-full transition-colors duration-300">
                EN
            </button>
        </div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 space-y-6">
            
            <div class="text-center">
                <i class="fa-solid fa-key text-blue-600 text-4xl mb-3"></i>
                <h1 id="form-title" class="text-2xl font-bold text-slate-800">Mot de passe oublié ?</h1>
                <p id="form-description" class="mt-2 text-sm text-slate-600">
                    Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien pour en choisir un nouveau.
                </p>
            </div>

            <div id="session-status" class="hidden p-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                Un lien de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.
            </div>

            <form method="POST" action="{{ route('password.email') }}"> <div>
                    <label for="email" id="email-label" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Adresse e-mail
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                           <i class="fa-solid fa-at text-slate-400"></i>
                        </div>
                        <input id="email" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                               type="email" 
                               name="email" 
                               required 
                               autofocus
                               placeholder="vous@exemple.com"
                        >
                    </div>
                     <div id="email-error" class="hidden mt-2 text-xs text-red-600">
                        Veuillez fournir une adresse e-mail valide.
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" id="submit-button" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 ease-in-out">
                        Envoyer le lien de réinitialisation
                    </button>
                </div>
            </form>
        </div>
         <footer class="text-center mt-8 text-sm text-slate-500">
            <p id="footer-text">© 2025 EasyGest Tous droits réservés.</p>
        </footer>
    </div>

    <script>
        // --- Dictionnaire de traductions ---
        const translations = {
            fr: {
                'page-title': 'Mot de passe oublié',
                'form-title': 'Mot de passe oublié ?',
                'form-description': 'Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien pour en choisir un nouveau.',
                'email-label': 'Adresse e-mail',
                'submit-button': 'Envoyer le lien de réinitialisation',
                'email-placeholder': 'vous@exemple.com',
                'footer-text': '© 2025 EasyGest Tous droits réservés.',
            },
            en: {
                'page-title': 'Forgot Password',
                'form-title': 'Forgot your password?',
                'form-description': 'No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
                'email-label': 'Email Address',
                'submit-button': 'Email Password Reset Link',
                'email-placeholder': 'you@example.com',
                'footer-text': '© 2025 EasyGest. All rights reserved.',
            }
        };

        // --- Fonction de changement de langue ---
        function switchLanguage(lang) {
            // S'assurer que la langue demandée existe
            if (!translations[lang]) return;

            const trans = translations[lang];
            
            // Appliquer les traductions au texte
            for (const key in trans) {
                const element = document.getElementById(key);
                if (element) {
                    if (key.includes('placeholder')) {
                        // Gérer les placeholders des inputs
                        document.getElementById('email').setAttribute('placeholder', trans['email-placeholder']);
                    } else {
                        element.textContent = trans[key];
                    }
                }
            }

            // Mettre à jour l'attribut lang de la balise <html> pour l'accessibilité
            document.getElementById('html-root').setAttribute('lang', lang);

            // Mettre à jour le style des boutons de langue
            document.getElementById('lang-fr').classList.toggle('active', lang === 'fr');
            document.getElementById('lang-en').classList.toggle('active', lang === 'en');
        }

        // --- Initialisation au chargement de la page ---
        document.addEventListener('DOMContentLoaded', () => {
            // Langue par défaut : français
            switchLanguage('fr');
        });
    </script>

</body>
</html>