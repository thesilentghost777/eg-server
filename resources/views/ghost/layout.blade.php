@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Module Administrateur' }}</h2>
                    <span class="text-sm text-gray-500">Accès Développeur</span>
                </div>

                <div class="flex mb-6">
                    <nav class="flex space-x-2">
                        <a href="{{ route('admin.index') }}" class="px-4 py-2 rounded {{ Request::routeIs('admin.index') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Tableau de bord
                        </a>
                        <a href="{{ route('admin.stats') }}" class="px-4 py-2 rounded {{ Request::routeIs('admin.stats') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Statistiques
                        </a>
                        <a href="{{ route('admin.logs') }}" class="px-4 py-2 rounded {{ Request::routeIs('admin.logs') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Logs
                        </a>
                        <a href="{{ route('admin.notifications') }}" class="px-4 py-2 rounded {{ Request::routeIs('admin.notifications') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Notifications <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-red-500 text-white">{{ $unreadNotificationsCount ?? 0 }}</span>
                        </a>
                     
                    </nav>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    @yield('admin-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
