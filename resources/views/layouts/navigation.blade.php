<nav x-data="{ open: false, langOpen: false, userOpen: false }" class="bg-white shadow-sm border-b border-gray-100 print:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('switch.workspace') }}">
                        {{-- Remplacez ce SVG par votre logo --}}
                        <svg class="block h-9 w-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 250 250" fill="none">
                            <circle cx="125" cy="125" r="120" stroke="#10B981" stroke-width="10"/>
                            <path d="M75 175V125C75 110 85 100 100 100H150" stroke="#3B82F6" stroke-width="15" stroke-linecap="round"/>
                            <path d="M175 75V125C175 140 165 150 150 150H100" stroke="#3B82F6" stroke-width="15" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('switch.workspace') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out
                              {{ request()->routeIs('dashboard') 
                                 ? 'border-blue-500 text-gray-900 focus:outline-none focus:border-blue-700' 
                                 : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}">
                        {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                    </a>
                    {{-- Ajoutez d'autres liens ici si nécessaire --}}
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative">
                    <div @click.away="langOpen = false" class="relative">
                        <button @click="langOpen = !langOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <i class="fa-solid fa-language text-xl"></i>
                        </button>
                        
                        <div x-show="langOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg origin-top-right z-50"
                             style="display: none;">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
    <form action="{{ route('lang.en') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-left block px-4 py-2 text-sm leading-5 {{ !$isFrench ? 'font-bold text-blue-600' : 'text-gray-700' }} hover:bg-gray-100">
            English
        </button>
    </form>

    <form action="{{ route('lang.fr') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-left block px-4 py-2 text-sm leading-5 {{ $isFrench ? 'font-bold text-blue-600' : 'text-gray-700' }} hover:bg-gray-100">
            Français
        </button>
    </form>
</div>

                        </div>
                    </div>
                </div>

                <div class="ms-3 relative">
                    <div @click.away="userOpen = false" class="relative">
                        <button @click="userOpen = !userOpen" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="userOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg origin-top-right z-50"
                             style="display: none;">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $isFrench ? 'Profil' : 'Profile' }}
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 hover:bg-red-50 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ $isFrench ? 'Déconnexion' : 'Log Out' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button onclick="window.history.back()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-blue-500 hover:bg-blue-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-green-500 hover:bg-green-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                   <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ $isFrench ? 'Profil' : 'Profile' }}
                </a>

                <div class="border-t border-gray-200 mt-2 pt-2">
    <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $isFrench ? 'Langue' : 'Language' }}</div>
    
    <!-- Formulaire pour English -->
    <form method="POST" action="{{ route('lang.en') }}" class="block">
        @csrf
        <button type="submit" class="w-full text-left ps-3 pe-4 py-2 border-l-4 {{ !$isFrench ? 'border-green-400 text-green-700 font-bold' : 'border-transparent text-gray-600' }} hover:bg-gray-50 text-base font-medium bg-transparent border-0 border-l-4">
            <i class="fa-solid fa-flag-usa me-2"></i> English
        </button>
    </form>
    
    <!-- Formulaire pour Français -->
    <form method="POST" action="{{ route('lang.fr') }}" class="block">
        @csrf
        <button type="submit" class="w-full text-left ps-3 pe-4 py-2 border-l-4 {{ $isFrench ? 'border-green-400 text-green-700 font-bold' : 'border-transparent text-gray-600' }} hover:bg-gray-50 text-base font-medium bg-transparent border-0 border-l-4">
            <i class="fa-solid fa-flag me-2"></i> Français
        </button>
    </form>
</div>
                
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 mt-2 pt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left block ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 focus:outline-none focus:text-red-800 focus:bg-red-50 focus:border-red-300 transition duration-150 ease-in-out"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                         <i class="fa-solid fa-right-from-bracket me-2"></i> {{ $isFrench ? 'Déconnexion' : 'Log Out' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>