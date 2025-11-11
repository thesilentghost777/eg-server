<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Gestion du Verrouillage' : 'Lock Management' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-amber-50 font-sans">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-lock"></i>
                        {{ $isFrench ? 'Gestion du Verrouillage' : 'Lock Management' }}
                    </h1>
                    <a href="{{ route('pdg.dashboard') }}" class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all duration-300 font-semibold">
                        <i class="fas fa-home mr-2"></i>{{ $isFrench ? 'Accueil' : 'Home' }}
                    </a>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                        <p class="font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-amber-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    {{ $isFrench ? 'Information' : 'Information' }}
                </h2>
                <p class="text-gray-700">
                    {{ $isFrench ? 'Cette page vous permet de verrouiller ou déverrouiller toutes les réceptions et retours d\'une date spécifique. Une fois verrouillé, les enregistrements ne pourront plus être modifiés ou supprimés.' : 'This page allows you to lock or unlock all receptions and returns for a specific date. Once locked, records cannot be modified or deleted.' }}
                </p>
            </div>

            <div class="space-y-4">
                @forelse($dates as $date)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <i class="fas fa-calendar-day"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                </h3>
                                @if($stats[$date]['tout_verrouille'])
                                    <span class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2">
                                        <i class="fas fa-lock"></i>{{ $isFrench ? 'Tout Verrouillé' : 'Fully Locked' }}
                                    </span>
                                @else
                                    <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2">
                                        <i class="fas fa-unlock"></i>{{ $isFrench ? 'Partiellement Ouvert' : 'Partially Open' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-600">
                                    <h4 class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-2xl font-bold text-blue-900">{{ $stats[$date]['receptions_count'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Total' : 'Total' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-red-800">{{ $stats[$date]['receptions_verrouillees'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Verrouillées' : 'Locked' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-600">
                                    <h4 class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">{{ $isFrench ? 'Retours' : 'Returns' }}</h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-2xl font-bold text-purple-900">{{ $stats[$date]['retours_count'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Total' : 'Total' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-red-800">{{ $stats[$date]['retours_verrouilles'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Verrouillés' : 'Locked' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <button onclick="handleLock('{{ $date }}', 'receptions')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    {{ $isFrench ? 'Verrouiller Réceptions' : 'Lock Receptions' }}
                                </button>

                                <button onclick="handleLock('{{ $date }}', 'retours')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    {{ $isFrench ? 'Verrouiller Retours' : 'Lock Returns' }}
                                </button>

                                <button onclick="handleLock('{{ $date }}', 'tous')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    {{ $isFrench ? 'Verrouiller Tout' : 'Lock All' }}
                                </button>
                            </div>

                            @if($stats[$date]['tout_verrouille'] || $stats[$date]['receptions_verrouillees'] > 0 || $stats[$date]['retours_verrouilles'] > 0)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <button onclick="handleUnlock('{{ $date }}')" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                        <i class="fas fa-unlock"></i>
                                        {{ $isFrench ? 'Déverrouiller Tout' : 'Unlock All' }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl font-semibold text-gray-600">{{ $isFrench ? 'Aucune date trouvée' : 'No dates found' }}</p>
                        <p class="text-gray-500 mt-2">{{ $isFrench ? 'Aucune réception ou retour enregistré' : 'No receptions or returns recorded' }}</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <script>
        function handleLock(date, type) {
            const labels = {
                'receptions': '{{ $isFrench ? "réceptions" : "receptions" }}',
                'retours': '{{ $isFrench ? "retours" : "returns" }}',
                'tous': '{{ $isFrench ? "réceptions et retours" : "receptions and returns" }}'
            };

            Swal.fire({
                title: '{{ $isFrench ? "Confirmer le verrouillage" : "Confirm Lock" }}',
                text: `{{ $isFrench ? "Voulez-vous vraiment verrouiller" : "Do you really want to lock" }} ${labels[type]} {{ $isFrench ? "du" : "of" }} ${date}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ $isFrench ? "Oui, verrouiller" : "Yes, lock" }}',
                cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("verrouillage.verrouiller") }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="date" value="${date}">
                        <input type="hidden" name="type" value="${type}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function handleUnlock(date) {
            Swal.fire({
                title: '{{ $isFrench ? "Confirmer le déverrouillage" : "Confirm Unlock" }}',
                text: `{{ $isFrench ? "Voulez-vous vraiment déverrouiller toutes les données du" : "Do you really want to unlock all data from" }} ${date}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ $isFrench ? "Oui, déverrouiller" : "Yes, unlock" }}',
                cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("verrouillage.deverrouiller") }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="date" value="${date}">
                        <input type="hidden" name="type" value="tous">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
