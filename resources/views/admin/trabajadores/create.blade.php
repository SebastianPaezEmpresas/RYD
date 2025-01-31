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
                <div class="flex items-center">
                    <button class="mr-4 p-2 text-gray-400 hover:text-emerald-500 transition-colors relative">
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

    <!-- Contenido -->
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-emerald-500 to-green-600">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user-plus mr-3"></i>
                        Crear Nuevo Trabajador
                    </h2>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.trabajadores.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-user mr-2 text-emerald-500"></i>
                                    Nombre
                                </label>
                                <input type="text" name="name" 
                                    class="form-input block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 ease-in-out 
                                    focus:border-emerald-500 focus:ring-emerald-500 hover:border-emerald-400"
                                    placeholder="Nombre del trabajador">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-emerald-500"></i>
                                    Email
                                </label>
                                <input type="email" name="email" 
                                    class="form-input block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 ease-in-out
                                    focus:border-emerald-500 focus:ring-emerald-500 hover:border-emerald-400"
                                    placeholder="ejemplo@email.com">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-lock mr-2 text-emerald-500"></i>
                                    Contraseña
                                </label>
                                <input type="password" name="password" 
                                    class="form-input block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 ease-in-out
                                    focus:border-emerald-500 focus:ring-emerald-500 hover:border-emerald-400"
                                    placeholder="••••••••">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-emerald-500"></i>
                                    Estado
                                </label>
                                <select name="active" 
                                    class="form-select block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 ease-in-out
                                    focus:border-emerald-500 focus:ring-emerald-500 hover:border-emerald-400">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('admin.trabajadores.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium 
                                text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium 
                                text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 
                                transition-all duration-150 ease-in-out hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                Crear Trabajador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection