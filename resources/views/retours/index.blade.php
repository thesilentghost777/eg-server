<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Gestion des Retours' : 'Returns Management' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-amber-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-undo-alt"></i>
                        {{ $isFrench ? 'Gestion des Retours' : 'Returns Management' }}
                    </h1>
                    <a href="{{ route('pdg.dashboard') }}" class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all duration-300 font-semibold">
                        <i class="fas fa-home mr-2"></i>{{ $isFrench ? 'Accueil' : 'Home' }}
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-amber-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Total Retours' : 'Total Returns' }}</p>
                            <p class="text-3xl font-bold text-amber-800 mt-2">{{ $retours->total() }}</p>
                        </div>
                        <i class="fas fa-boxes text-4xl text-amber-300"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Verrouillés' : 'Locked' }}</p>
                            <p class="text-3xl font-bold text-red-800 mt-2">{{ $retours->where('verrou', true)->count() }}</p>
                        </div>
                        <i class="fas fa-lock text-4xl text-red-300"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Modifiables' : 'Editable' }}</p>
                            <p class="text-3xl font-bold text-green-800 mt-2">{{ $retours->where('verrou', false)->count() }}</p>
                        </div>
                        <i class="fas fa-unlock text-4xl text-green-300"></i>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-amber-700 to-amber-800 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Raison' : 'Reason' }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($retours as $retour)
                                <tr class="hover:bg-amber-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $retour->date_retour->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $retour->produit->nom }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <span class="font-bold text-amber-700">{{ $retour->quantite }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $raisonColors = [
                                                'perime' => 'bg-red-100 text-red-800',
                                                'abime' => 'bg-orange-100 text-orange-800',
                                                'autre' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $raisonLabels = [
                                                'perime' => $isFrench ? 'Périmé' : 'Expired',
                                                'abime' => $isFrench ? 'Abîmé' : 'Damaged',
                                                'autre' => $isFrench ? 'Autre' : 'Other'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $raisonColors[$retour->raison] }}">
                                            {{ $raisonLabels[$retour->raison] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $retour->vendeur->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($retour->verrou)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-lock mr-2"></i>{{ $isFrench ? 'Verrouillé' : 'Locked' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-unlock mr-2"></i>{{ $isFrench ? 'Modifiable' : 'Editable' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('retours.show', $retour) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$retour->verrou)
                                                <a href="{{ route('retours.edit', $retour) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmDelete({{ $retour->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                                            <p class="text-lg font-semibold">{{ $isFrench ? 'Aucun retour enregistré' : 'No returns recorded' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($retours->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        {{ $retours->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '{{ $isFrench ? "Êtes-vous sûr?" : "Are you sure?" }}',
                text: '{{ $isFrench ? "Cette action est irréversible!" : "This action is irreversible!" }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ $isFrench ? "Oui, supprimer" : "Yes, delete" }}',
                cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/retours/${id}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
