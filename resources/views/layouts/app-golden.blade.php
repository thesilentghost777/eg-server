<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EasyGest BP') - Boulangerie Pâtisserie</title>
    
    <!-- Fonts -->
    <style>
        @font-face {
            font-family: 'Figtree';
            src: url('/fonts/Figtree-Regular.ttf') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Figtree';
            src: url('/fonts/Figtree-Medium.ttf') format('truetype');
            font-weight: 500;
        }
        @font-face {
            font-family: 'Figtree';
            src: url('/fonts/Figtree-SemiBold.ttf') format('truetype');
            font-weight: 600;
        }
        @font-face {
            font-family: 'Figtree';
            src: url('/fonts/Figtree-Bold.ttf') format('truetype');
            font-weight: 700;
        }
    </style>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'figtree': ['Figtree', 'sans-serif'],
                    },
                    colors: {
                        gold: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        },
                        bronze: {
                            500: '#cd7f32',
                            600: '#b87333',
                            700: '#8b5a2b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .gradient-gold {
            background: linear-gradient(135deg, #fef08a 0%, #eab308 50%, #ca8a04 100%);
        }
        .gradient-gold-dark {
            background: linear-gradient(135deg, #854d0e 0%, #a16207 50%, #ca8a04 100%);
        }
        .text-gold-gradient {
            background: linear-gradient(135deg, #fde047 0%, #eab308 50%, #a16207 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-golden {
            background: linear-gradient(145deg, #1f2937 0%, #111827 100%);
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .card-golden:hover {
            border-color: rgba(234, 179, 8, 0.6);
            box-shadow: 0 0 20px rgba(234, 179, 8, 0.15);
        }
        .btn-gold {
            background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
            color: #1f2937;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #fde047 0%, #eab308 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(234, 179, 8, 0.4);
        }
        .input-golden {
            background: rgba(31, 41, 55, 0.8);
            border: 1px solid rgba(234, 179, 8, 0.3);
            color: #fef9c3;
        }
        .input-golden:focus {
            border-color: #eab308;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.2);
            outline: none;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, rgba(234, 179, 8, 0.2) 0%, transparent 100%);
            border-left: 3px solid #eab308;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen font-figtree">
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gold-600/30 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full gradient-gold flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gold-gradient">EasyGest BP</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gold-300">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'N/A')) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full gradient-gold-dark flex items-center justify-center">
                            <span class="text-gold-200 font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-gold-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 min-h-[calc(100vh-4rem)] border-r border-gold-600/20 hidden lg:block">
            <nav class="py-6 px-4 space-y-2">
                @yield('sidebar')
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Alerts -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-900/50 border border-green-500/50 text-green-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-900/50 border border-red-500/50 text-red-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="mb-6 p-4 rounded-lg bg-blue-900/50 border border-blue-500/50 text-blue-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('info') }}
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Nav -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gold-600/30 px-4 py-2">
        <div class="flex justify-around">
            @yield('mobile-nav')
        </div>
    </nav>

    @yield('scripts')
</body>
</html>
