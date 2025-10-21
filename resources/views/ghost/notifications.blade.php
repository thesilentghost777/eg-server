@extends('ghost.layout')

@section('admin-content')
<h3 class="text-xl font-bold text-gray-800 mb-4">Notifications Système</h3>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('admin.notifications') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Type de notification</label>
            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Tous les types</option>
                @foreach($notificationTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ class_basename($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Tous</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lus</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lus</option>
                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Traités</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Filtrer
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Contenu</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Utilisateur</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notification)
            <tr class="border-b {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                <td class="px-4 py-2 text-sm">
                    {{ class_basename($notification->type) }}
                </td>
                <td class="px-4 py-2 text-sm max-w-md truncate">
                    <details class="cursor-pointer">
                        <summary>{{ json_encode($notification->data) }}</summary>
                        <pre class="text-xs mt-2 bg-gray-50 p-2 rounded">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                </td>
                <td class="px-4 py-2 text-sm">
                    @if($notification->notifiable)
                        {{ optional($notification->notifiable)->name ?? 'ID: ' . $notification->notifiable_id }}
                    @else
                        Utilisateur supprimé
                    @endif
                </td>
                <td class="px-4 py-2 text-sm">
                    {{ $notification->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-4 py-2 text-sm">
                    @if($notification->processed)
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Traité</span>
                    @elseif(!is_null($notification->read_at))
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Lu</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Non lu</span>
                    @endif
                </td>
                <td class="px-4 py-2 text-sm">
                    <div class="flex space-x-2">
                        @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs">
                                Marquer lu
                            </button>
                        </form>
                        @endif
                        
                        @if(!$notification->processed)
                        <form action="{{ route('notifications.mark-processed', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs">
                                Marquer traité
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                    Aucune notification trouvée.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-4">
        {{ $notifications->links() }}
    </div>
</div>

@endsection