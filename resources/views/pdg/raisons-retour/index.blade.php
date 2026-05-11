@extends('layouts.app')

@section('title', $isFrench ? 'Raisons de Retour' : 'Return Reasons')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-red-50">

    <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-tags"></i>
                    {{ $isFrench ? 'Raisons de Retour' : 'Return Reasons' }}
                </h1>
                <div class="flex gap-3">
                    <a href="{{ route('pdg.raisons-retour.create') }}"
                       class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all font-semibold">
                        <i class="fas fa-plus mr-2"></i>{{ $isFrench ? 'Nouvelle raison' : 'New reason' }}
                    </a>
                    <a href="{{ route('pdg.dashboard') }}"
                       class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-all font-semibold">
                        <i class="fas fa-home mr-2"></i>{{ $isFrench ? 'Accueil' : 'Home' }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-amber-700 to-amber-800 px-6 py-4">
                <h2 class="text-white font-bold text-lg">
                    {{ $isFrench ? 'Liste des raisons' : 'Reasons list' }}
                    <span class="ml-2 bg-white/20 text-white text-sm px-2 py-0.5 rounded-full">{{ $raisons->count() }}</span>
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-amber-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-800 uppercase">{{ $isFrench ? 'Code' : 'Code' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-800 uppercase">{{ $isFrench ? 'Libellé' : 'Label' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-800 uppercase">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-amber-800 uppercase">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($raisons as $raison)
                            <tr class="hover:bg-amber-50 transition-colors">
                                <td class="px-6 py-4">
                                    <code class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm font-mono">{{ $raison->code }}</code>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $raison->libelle }}</td>
                                <td class="px-6 py-4">
                                    @if($raison->actif)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>{{ $isFrench ? 'Actif' : 'Active' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            <i class="fas fa-times-circle mr-1"></i>{{ $isFrench ? 'Inactif' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pdg.raisons-retour.edit', $raison->id) }}"
                                           class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all">
                                            <i class="fas fa-edit mr-1"></i>{{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        <form method="POST" action="{{ route('pdg.raisons-retour.toggle', $raison->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="{{ $raison->actif ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all">
                                                <i class="fas fa-{{ $raison->actif ? 'eye-slash' : 'eye' }} mr-1"></i>
                                                {{ $raison->actif ? ($isFrench ? 'Désactiver' : 'Disable') : ($isFrench ? 'Activer' : 'Enable') }}
                                            </button>
                                        </form>
                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-tags text-5xl mb-4 text-gray-300 block"></i>
                                    {{ $isFrench ? 'Aucune raison définie' : 'No reasons defined' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-blue-800 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                {{ $isFrench
                    ? 'Les raisons actives sont synchronisées automatiquement vers tous les clients mobiles lors de leur prochaine synchronisation.'
                    : 'Active reasons are automatically synced to all mobile clients on their next sync.' }}
            </p>
        </div>
    </main>
</div>
@endsection