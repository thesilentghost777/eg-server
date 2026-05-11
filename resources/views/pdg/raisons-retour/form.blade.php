@extends('layouts.app')

@section('title', isset($raison) ? ($isFrench ? 'Modifier la raison' : 'Edit reason') : ($isFrench ? 'Nouvelle raison' : 'New reason'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-red-50">

    <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-{{ isset($raison) ? 'edit' : 'plus-circle' }}"></i>
                    {{ isset($raison) ? ($isFrench ? 'Modifier la raison' : 'Edit reason') : ($isFrench ? 'Nouvelle raison de retour' : 'New return reason') }}
                </h1>
                <a href="{{ route('pdg.raisons-retour.index') }}"
                   class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 max-w-lg">

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST"
                  action="{{ isset($raison) ? route('pdg.raisons-retour.update', $raison->id) : route('pdg.raisons-retour.store') }}">
                @csrf
                @if(isset($raison))
                    @method('PUT')
                @endif

                {{-- Libellé --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Libellé affiché *' : 'Display label *' }}
                    </label>
                    <input type="text"
                           name="libelle"
                           value="{{ old('libelle', $raison->libelle ?? '') }}"
                           placeholder="{{ $isFrench ? 'Ex: Mauvaise qualité' : 'Ex: Poor quality' }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base"
                           required>
                    <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Texte affiché aux utilisateurs mobiles.' : 'Text shown to mobile users.' }}</p>
                </div>

                {{-- Code --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Code interne *' : 'Internal code *' }}
                    </label>
                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code', $raison->code ?? '') }}"
                           placeholder="ex: mauvaise_qualite"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent font-mono text-base {{ isset($raison) && in_array($raison->code, ['perime','abime','autre']) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                           {{ isset($raison) && in_array($raison->code, ['perime','abime','autre']) ? 'readonly' : 'required' }}>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $isFrench ? 'Minuscules, chiffres et underscores uniquement. Ex: mauvaise_qualite, non_conforme' : 'Lowercase, digits and underscores only. Ex: poor_quality, non_compliant' }}
                    </p>
                    @if(isset($raison) && in_array($raison->code, ['perime','abime','autre']))
                        <p class="text-xs text-orange-600 mt-1 font-medium">
                            <i class="fas fa-lock mr-1"></i>{{ $isFrench ? 'Code système non modifiable.' : 'System code, cannot be changed.' }}
                        </p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-3 px-6 rounded-lg font-semibold transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        {{ isset($raison) ? ($isFrench ? 'Mettre à jour' : 'Update') : ($isFrench ? 'Créer la raison' : 'Create reason') }}
                    </button>
                    <a href="{{ route('pdg.raisons-retour.index') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all font-semibold text-center">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
// Auto-générer le code depuis le libellé si le champ code est vide
document.querySelector('input[name="libelle"]')?.addEventListener('input', function() {
    const codeField = document.getElementById('code');
    if (!codeField || codeField.readOnly) return;
    // Seulement si l'utilisateur n'a pas déjà tapé dans le champ code
    if (codeField.dataset.touched) return;
    const slug = this.value
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')  // enlever accents
        .replace(/[^a-z0-9\s_]/g, '')
        .trim()
        .replace(/\s+/g, '_');
    codeField.value = slug;
});
document.getElementById('code')?.addEventListener('input', function() {
    this.dataset.touched = '1';
    // Forcer minuscules et underscores
    this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
});
</script>
@endsection