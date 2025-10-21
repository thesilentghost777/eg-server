<!DOCTYPE html>
<html lang="fr" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Vérification de l'E-mail</title>

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
                <i class="fa-solid fa-envelope-circle-check text-blue-600 text-4xl mb-3"></i>
                <h1 id="form-title" class="text-2xl font-bold text-slate-800">Vérifiez votre adresse e-mail</h1>
            </div>

            <p id="form-description" class="text-sm text-slate-600 text-center">
                Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre avec plaisir.
            </p>

            <div id="success-message" class="hidden p-4 font-medium text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de votre inscription.
            </div>

            <div class="mt-4 flex items-center justify-between">
                
                <form method="POST" action="#"> <button type="submit" id="resend-button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 ease-in-out">
                        Renvoyer l'e-mail de vérification
                    </button>
                </form>

                <form method="POST" action="#"> <button type="submit" id="logout-button" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
         <footer class="text-center mt-8 text-sm text-slate-500">
            <p id="footer-text">© 2025 VotreEntreprise. Tous droits réservés.</p>
        </footer>
    </div>

    <script>
        // --- Dictionnaire de traductions ---
        const translations = {
            fr: {
                'page-title': "Vérification de l'E-mail",
                'form-title': 'Vérifiez votre adresse e-mail',
                'form-description': "Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre avec plaisir.",
                'success-message': "Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de votre inscription.",
                'resend-button': "Renvoyer l'e-mail de vérification",
                'logout-button': 'Se déconnecter',
                'footer-text': '© 2025 VotreEntreprise. Tous droits réservés.',
            },
            en: {
                'page-title': 'Email Verification',
                'form-title': 'Verify Your Email Address',
                'form-description': "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
                'success-message': 'A new verification link has been sent to the email address you provided during registration.',
                'resend-button': 'Resend Verification Email',
                'logout-button': 'Log Out',
                'footer-text': '© 2025 YourCompany. All rights reserved.',
            }
        };

        // --- Fonction de changement de langue ---
        function switchLanguage(lang) {
            if (!translations[lang]) return;

            const trans = translations[lang];
            
            for (const key in trans) {
                const element = document.getElementById(key);
                if (element) {
                    element.textContent = trans[key];
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