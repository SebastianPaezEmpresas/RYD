@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img class="h-10 w-auto" src="{{ asset('img/logo.png') }}" alt="RYD">
                    <div class="ml-8 flex space-x-6">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2">
                            <i class="fas fa-home mr-2 text-gray-400 group-hover:text-emerald-500 transition-colors"></i>
                            <span class="text-gray-500 group-hover:text-gray-900">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.trabajadores.index') }}" class="group flex items-center px-3 py-2 border-b-2 border-emerald-500">
                            <i class="fas fa-users mr-2 text-emerald-500"></i>
                            <span class="text-gray-900">Trabajadores</span>
                        </a>
                        <a href="{{ route('admin.trabajos.index') }}" class="group flex items-center px-3 py-2">
                            <i class="fas fa-briefcase mr-2 text-gray-400 group-hover:text-emerald-500 transition-colors"></i>
                            <span class="text-gray-500 group-hover:text-gray-900">Trabajos</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 text-gray-400 hover:text-emerald-500 transition-colors relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-emerald-500 rounded-full"></span>
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="ml-4">
                        @csrf
                        <button class="group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-lg flex items-center">
                            <i class="fas fa-sign-out-alt mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <div class="px-8 py-6 bg-gradient-to-r from-emerald-500 to-green-600 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Gestión de Trabajadores
                    </h2>
                    <a href="{{ route('admin.trabajadores.create') }}" 
                        class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Trabajador
                    </a>
                </div>

                <div class="p-6">
                    <!-- Search and filters -->
                    <div class="mb-6 flex justify-between items-center">
                        <div class="relative">
                            <input type="text" placeholder="Buscar trabajador..." 
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-64">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="flex space-x-4">
                            <select class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option>Todos los estados</option>
                                <option>Activos</option>
                                <option>Inactivos</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($workers as $worker)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                    <span class="text-emerald-600 font-medium text-sm">{{ substr($worker->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $worker->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $worker->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($worker->active)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                <i class="fas fa-check-circle mr-1"></i> Activo
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.trabajadores.edit', $worker) }}" 
                                            class="inline-flex items-center p-2 text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50 rounded-lg transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.trabajadores.destroy', $worker) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors"
                                                onclick="return confirm('¿Estás seguro de eliminar este trabajador?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection