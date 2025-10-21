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
            /* Splash Screen Styles - Couleur Pain Doré */
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
            }

            /* Animation de cercle tournant */
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

            /* Effet de pulsation sur le logo */
            .splash-logo {
                animation: logo-pulse 3s ease-in-out infinite;
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

            /* Ajustements mobiles */
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

            /* Effet de particules flottantes */
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
        </style>

        @PwaHead
    </head>
    <body class="font-sans antialiased">
        @include('partials._sweetalert')

        <!-- Splash Screen - Couleur Pain Doré -->
        <div id="splash-screen">
            <div class="splash-particles">
                <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
                <div class="particle" style="left: 20%; animation-delay: 0.5s;"></div>
                <div class="particle" style="left: 30%; animation-delay: 1s;"></div>
                <div class="particle" style="left: 40%; animation-delay: 1.5s;"></div>
                <div class="particle" style="left: 50%; animation-delay: 2s;"></div>
                <div class="particle" style="left: 60%; animation-delay: 2.5s;"></div>
                <div class="particle" style="left: 70%; animation-delay: 3s;"></div>
                <div class="particle" style="left: 80%; animation-delay: 3.5s;"></div>
                <div class="particle" style="left: 90%; animation-delay: 4s;"></div>
            </div>
            
            <div class="splash-logo-container">
                <div class="hostinger-ring"></div>
                <div class="splash-logo">
                    EG
                </div>
            </div>
            
            <div class="splash-text">
                {{ $isFrench ? 'chargement...' : 'loading...' }}
            </div>
        </div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <script>
            // Gestion du splash screen
            document.addEventListener('DOMContentLoaded', () => {
                // Affiche instantanément
                document.getElementById('splash-screen').classList.remove('hide');
                
                // Force la fermeture après 3 secondes maximum
                setTimeout(() => {
                    document.getElementById('splash-screen').classList.add('hide');
                }, 3000);
            });

            window.addEventListener('load', () => {
                // Masque après chargement complet (si moins de 3s)
                setTimeout(() => document.getElementById('splash-screen').classList.add('hide'), 100);
            });

            // Ré-expose le splash sur navigation interne
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', e => {
                    const url = link.getAttribute('href');
                    if (!url.startsWith('#') && !link.hasAttribute('target')) {
                        document.getElementById('splash-screen').classList.remove('hide');
                        
                        // Force la fermeture après 3 secondes maximum pour la navigation
                        setTimeout(() => {
                            document.getElementById('splash-screen').classList.add('hide');
                        }, 3000);
                    }
                });
            });
        </script>

        @RegisterServiceWorkerScript
    </body>
</html>