<nav x-data="{ open: false, langOpen: false, userOpen: false }" class="bg-white shadow-sm border-b border-gray-100 print:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('pdg.dashboard') }}">
                        <img src="{{ asset('logo_officiel.png') }}" alt="Logo" class="block h-12 w-auto max-w-[180px] object-contain">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('pdg.dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out
                              {{ request()->routeIs('dashboard') 
                                 ? 'border-blue-500 text-gray-900 focus:outline-none focus:border-blue-700' 
                                 : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}">
                        {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative">
                    <div @click.away="langOpen = false" class="relative">
                        <button @click="langOpen = !langOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-green-500 hover:bg-green-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
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
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    {{ $isFrench ? 'Langue' : 'Language' }}
                </div>
                
                <form method="POST" action="{{ route('lang.en') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left ps-3 pe-4 py-2 border-l-4 {{ !$isFrench ? 'border-green-400 text-green-700 font-bold' : 'border-transparent text-gray-600' }} hover:bg-gray-50 text-base font-medium">
                        <svg class="w-4 h-4 inline-block me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        English
                    </button>
                </form>
                
                <form method="POST" action="{{ route('lang.fr') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left ps-3 pe-4 py-2 border-l-4 {{ $isFrench ? 'border-green-400 text-green-700 font-bold' : 'border-transparent text-gray-600' }} hover:bg-gray-50 text-base font-medium">
                        <svg class="w-4 h-4 inline-block me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        Français
                    </button>
                </form>
            </div>
                
            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 mt-2 pt-2">
                @csrf
                <button type="submit" 
                        class="w-full text-left block ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 focus:outline-none focus:text-red-800 focus:bg-red-50 focus:border-red-300 transition duration-150 ease-in-out"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    <svg class="w-4 h-4 inline-block me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    {{ $isFrench ? 'Déconnexion' : 'Log Out' }}
                </button>
            </form>
        </div>
    </div>
</nav>