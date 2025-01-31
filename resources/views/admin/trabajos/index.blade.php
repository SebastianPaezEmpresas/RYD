@extends('admin.layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <nav class="bg-white/80 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img class="h-10 w-auto" src="{{ asset('img/logo.png') }}" alt="RYD">
                    <div class="ml-8 flex space-x-6">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2">
                            <i class="fas fa-home mr-2 text-gray-400 group-hover:text-emerald-500"></i>
                            <span class="text-gray-500 group-hover:text-gray-900">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.trabajadores.index') }}" class="group flex items-center px-3 py-2">
                            <i class="fas fa-users mr-2 text-gray-400 group-hover:text-emerald-500"></i>
                            <span class="text-gray-500 group-hover:text-gray-900">Trabajadores</span>
                        </a>
                        <a href="{{ route('admin.trabajos.index') }}" class="group flex items-center px-3 py-2 border-b-2 border-emerald-500">
                            <i class="fas fa-briefcase mr-2 text-emerald-500"></i>
                            <span class="text-gray-900">Trabajos</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-400 hover:text-emerald-500 transition-colors relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-emerald-500 rounded-full"></span>
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-sign-out-alt mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-6 rounded-lg mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-briefcase"></i> Gestión de Trabajos
                </h1>
                <div class="flex gap-3">
                    <a href="{{ route('admin.trabajos.schedule') }}" 
                       class="bg-white/90 text-emerald-600 px-4 py-2 rounded-lg font-medium hover:bg-white transition-colors flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i> Programar Conjunto
                    </a>
                    <a href="{{ route('admin.trabajos.create') }}" 
                       class="bg-white text-emerald-600 px-4 py-2 rounded-lg font-medium hover:bg-white/90 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Nuevo Trabajo
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-6">
            <div class="flex justify-between mb-6">
                <div class="flex gap-4">
                    <select name="estado" class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <option value="">Todos los Estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_progreso">En Progreso</option>
                        <option value="completado">Completado</option>
                    </select>
                    <input type="date" class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trabajo</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trabajador</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($trabajos as $trabajo)
                        <tr class="hover:bg-emerald-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    <a href="{{ route('admin.trabajos.show', $trabajo) }}" class="hover:text-emerald-600">
                                        {{ $trabajo->tipo_trabajo }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">{{ Str::limit($trabajo->descripcion, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center mr-3">
                                        <span class="text-emerald-600 font-medium text-sm">{{ substr($trabajo->worker->name, 0, 2) }}</span>
                                    </div>
                                    <span class="text-gray-900">{{ $trabajo->worker->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $trabajo->fecha_programada->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium inline-flex items-center
                                    @if($trabajo->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($trabajo->estado === 'en_progreso') bg-blue-100 text-blue-800
                                    @else bg-emerald-100 text-emerald-800
                                    @endif">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ Str::title(str_replace('_', ' ', $trabajo->estado)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.trabajos.show', $trabajo) }}" 
                                       class="p-1 text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50 rounded-lg transition-colors" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.trabajos.edit', $trabajo) }}" 
                                       class="p-1 text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50 rounded-lg transition-colors" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="if(confirm('¿Estás seguro de eliminar este trabajo?')) { 
                                        document.getElementById('delete-form-{{ $trabajo->id }}').submit(); 
                                    }" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $trabajo->id }}" 
                                          action="{{ route('admin.trabajos.destroy', $trabajo) }}" 
                                          method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $trabajos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection