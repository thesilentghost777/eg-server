<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Détails du Retour' : 'Return Details' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-amber-50 font-sans">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-file-alt"></i>
                        {{ $isFrench ? 'Détails du Retour' : 'Return Details' }}
                    </h1>
                    <a href="{{ route('retours.index') }}" class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all duration-300 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                    </a>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Date du Retour' : 'Return Date' }}</label>
                                <p class="text-lg font-bold text-amber-900 bg-amber-50 px-4 py-3 rounded-lg">
                                    {{ $retour->date_retour->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                                @if($retour->verrou)
                                    <p class="inline-flex items-center px-4 py-3 rounded-lg text-sm font-bold bg-red-100 text-red-800">
                                        <i class="fas fa-lock mr-2"></i>{{ $isFrench ? 'Verrouillé' : 'Locked' }}
                                    </p>
                                @else
                                    <p class="inline-flex items-center px-4 py-3 rounded-lg text-sm font-bold bg-green-100 text-green-800">
                                        <i class="fas fa-unlock mr-2"></i>{{ $isFrench ? 'Modifiable' : 'Editable' }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</label>
                                <p class="text-lg font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                                    {{ $retour->produit->nom }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                <p class="text-lg font-bold text-amber-900 bg-amber-50 px-4 py-3 rounded-lg">
                                    {{ $retour->quantite }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Raison du Retour' : 'Return Reason' }}</label>
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
                            <p class="inline-block px-4 py-3 rounded-lg text-sm font-bold {{ $raisonColors[$retour->raison] }}">
                                {{ $raisonLabels[$retour->raison] }}
                            </p>
                        </div>

                        @if($retour->description)
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Description' : 'Description' }}</label>
                                <p class="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                                    {{ $retour->description }}
                                </p>
                            </div>
                        @endif

                        <hr class="border-gray-200">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Pointeur' : 'Counter' }}</label>
                                <p class="text-lg font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                                    {{ $retour->pointeur->name }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Vendeur' : 'Seller' }}</label>
                                <p class="text-lg font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                                    {{ $retour->vendeur->name }}
                                </p>
                            </div>
                        </div>

                        @if(!$retour->verrou)
                            <div class="flex gap-4 pt-4">
                                <a href="{{ route('retours.edit', $retour) }}" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg text-center">
                                    <i class="fas fa-edit mr-2"></i>{{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <button onclick="confirmDelete()" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-trash mr-2"></i>{{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function confirmDelete() {
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
                    form.action = '{{ route('retours.destroy', $retour) }}';
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
