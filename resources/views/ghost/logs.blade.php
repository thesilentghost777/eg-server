@extends('ghost.layout')

@section('admin-content')
<h3 class="text-xl font-bold text-gray-800 mb-4">Logs Système</h3>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('admin.logs') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <select name="date" class="w-full border-gray-300 rounded-md shadow-sm">
                @foreach($availableDates as $availableDate)
                    <option value="{{ $availableDate }}" {{ $availableDate == $date ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($availableDate)->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Afficher
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow p-4">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-semibold text-gray-700">Fichier de log: {{ $date }}</h4>
        <div>
            <button onclick="copyLogsToClipboard()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs">
                Copier
            </button>
        </div>
    </div>
    
    <div class="overflow-auto max-h-[600px] bg-gray-100 p-3 rounded text-xs font-mono" id="logContent">
        <pre>{{ $logContent }}</pre>
    </div>
</div>

<script>
function copyLogsToClipboard() {
    const logContent = document.getElementById('logContent').innerText;
    navigator.clipboard.writeText(logContent).then(() => {
        alert('Logs copiés dans le presse-papier');
    });
}
</script>
@endsection
