@extends('layouts.app')

@section('title', $isFrench ? 'Gestion des Utilisateurs' : 'User Management')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    .user-card-hover {
        transition: all 0.3s ease;
    }
    .user-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(180, 133, 84, 0.3);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
<div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6 sm:mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                <i class="fas fa-users mr-3"></i>
                {{ $isFrench ? 'Gestion des Utilisateurs' : 'User Management' }}
            </h1>
            <p class="text-amber-50 text-sm sm:text-base">
                {{ $isFrench ? 'Gérez tous les utilisateurs du système' : 'Manage all system users' }}
            </p>
        </div>
        <a href="{{ route('users.create') }}"
           class="bg-white text-amber-700 px-6 py-3 rounded-xl font-semibold hover:bg-amber-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>
            {{ $isFrench ? 'Nouvel Utilisateur' : 'New User' }}
        </a>
    </div>
</div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('users.index') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ !isset($role) ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    <i class="fas fa-users mr-2"></i>{{ $isFrench ? 'Tous' : 'All' }}
                </a>
                <a href="{{ route('users.parRole', 'pdg') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ isset($role) && $role == 'pdg' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    <i class="fas fa-crown mr-2"></i>PDG
                </a>
                <a href="{{ route('users.parRole', 'pointeur') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ isset($role) && $role == 'pointeur' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    <i class="fas fa-clipboard-check mr-2"></i>{{ $isFrench ? 'Pointeurs' : 'Pointers' }}
                </a>
                <a href="{{ route('users.parRole', 'vendeur_boulangerie') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ isset($role) && $role == 'vendeur_boulangerie' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    <i class="fas fa-bread-slice mr-2"></i>{{ $isFrench ? 'V. Boulangerie' : 'B. Sellers' }}
                </a>
                <a href="{{ route('users.parRole', 'vendeur_patisserie') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ isset($role) && $role == 'vendeur_patisserie' ? 'bg-pink-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    <i class="fas fa-birthday-cake mr-2"></i>{{ $isFrench ? 'V. Pâtisserie' : 'P. Sellers' }}
                </a>
                <a href="{{ route('users.producteurs') }}" 
                   class="px-4 py-2 rounded-lg font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                    <i class="fas fa-industry mr-2"></i>{{ $isFrench ? 'Producteurs' : 'Producers' }}
                </a>
            </div>
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @forelse($users as $user)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden user-card-hover">
                <div class="p-6">
                    <!-- Avatar & Status -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full {{ $user->actif ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                <i class="fas fa-user text-xl {{ $user->actif ? 'text-green-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $user->nom }}</h3>
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    @if($user->role == 'pdg') bg-red-100 text-red-700
                                    @elseif($user->role == 'pointeur') bg-blue-100 text-blue-700
                                    @elseif($user->role == 'vendeur_boulangerie') bg-green-100 text-green-700
                                    @elseif($user->role == 'vendeur_patisserie') bg-pink-100 text-pink-700
                                    @else bg-purple-100 text-purple-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone w-5"></i>
                            <span>{{ $user->numero_telephone }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-circle w-5 {{ $user->actif ? 'text-green-500' : 'text-gray-400' }}"></i>
                            <span class="{{ $user->actif ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $user->actif ? ($isFrench ? 'Actif' : 'Active') : ($isFrench ? 'Inactif' : 'Inactive') }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg text-center text-sm font-medium transition-all">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('users.toggleActif', $user->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full {{ $user->actif ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-2 px-3 rounded-lg text-sm font-medium transition-all">
                                <i class="fas fa-{{ $user->actif ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <button onclick="confirmDelete({{ $user->id }})" 
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucun utilisateur trouvé' : 'No users found' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(userId) {
    Swal.fire({
        title: '{{ $isFrench ? "Êtes-vous sûr ?" : "Are you sure?" }}',
        text: '{{ $isFrench ? "Cette action est irréversible !" : "This action cannot be undone!" }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ $isFrench ? "Oui, supprimer" : "Yes, delete" }}',
        cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/users/${userId}`;
            form.submit();
        }
    });
}
</script>
@endsection
