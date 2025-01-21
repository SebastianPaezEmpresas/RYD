@extends('admin.layouts.app')

@section('content')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Navegación Superior Mejorada -->
<nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <!-- Logo -->
                    <img class="h-8 w-auto" src="{{ asset('img/logo.png') }}" alt="RYD Jardinería">
                </div>
                <!-- Enlaces de Navegación Principal -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.trabajadores.index') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Trabajadores
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Trabajos
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Encuestas
                    </a>
                </div>
            </div>
            <!-- Menú Usuario -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Ver notificaciones</span>
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <!-- Botón Cerrar Sesión -->
                <div class="ml-3 relative">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Contenido Principal -->
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen pt-20 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Inicio</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                        <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header con Acciones -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-5xl font-extrabold text-gray-800 animate__animated animate__fadeIn flex items-center">
                Dashboard de 
                <span class="text-indigo-600 ml-2 relative group">
                    RYD Jardinería
                    <span class="absolute bottom-0 left-0 w-full h-1 bg-indigo-600 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
                </span>
            </h1>
            <!-- Botones de Acción Rápida -->
            <div class="flex space-x-4">
                <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Trabajo
                </button>
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-download mr-2"></i>
                    Exportar Datos
                </button>
            </div>
        </div>

        <!-- Filtros y Período -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow-sm flex justify-between items-center">
            <div class="flex space-x-4">
                <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>Todos los Estados</option>
                    <option>Pendientes</option>
                    <option>En Progreso</option>
                    <option>Completados</option>
                </select>
                <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>Todos los Trabajadores</option>
                    <option>Activos</option>
                    <option>Inactivos</option>
                </select>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-500">Período:</span>
                <div class="relative">
                    <input type="date" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Primera fila de tarjetas principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tarjeta: Total de Trabajadores -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 overflow-hidden relative group">
                <div class="absolute inset-0 bg-indigo-600 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-users mr-3 text-indigo-600 text-3xl"></i>
                        <span>Total de Trabajadores</span>
                    </h2>
                    <button class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <p class="text-7xl font-extrabold text-indigo-600 mt-4 animate__animated animate__fadeIn">
                    {{ $totalTrabajadores }}
                </p>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center text-green-500">
                        <i class="fas fa-arrow-up mr-1"></i>
                        12%
                    </span>
                    <span class="ml-2">vs mes anterior</span>
                </div>
            </div>

            <!-- Tarjeta: Trabajos Completados -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 overflow-hidden relative group">
                <div class="absolute inset-0 bg-green-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-check-circle mr-3 text-green-500 text-3xl"></i>
                        <span>Trabajos Completados</span>
                    </h2>
                    <button class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <p class="text-7xl font-extrabold text-green-500 mt-4 animate__animated animate__fadeIn">
                    {{ $trabajosCompletados }}
                </p>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center text-green-500">
                        <i class="fas fa-arrow-up mr-1"></i>
                        8%
                    </span>
                    <span class="ml-2">de eficiencia</span>
                </div>
            </div>

            <!-- Tarjeta: Nivel de Satisfacción -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 overflow-hidden relative group">
                <div class="absolute inset-0 bg-yellow-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-smile mr-3 text-yellow-500 text-3xl"></i>
                        <span>Nivel de Satisfacción</span>
                    </h2>
                    <button class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <p class="text-7xl font-extrabold text-yellow-500 mt-4 animate__animated animate__fadeIn">
                    {{ number_format($satisfaccionPromedio, 1) }}%
                </p>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center text-yellow-500">
                        <i class="fas fa-star mr-1"></i>
                        4.8/5
                    </span>
                    <span class="ml-2">valoración promedio</span>
                </div>
            </div>
        </div>

        <!-- Segunda fila de tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            <!-- Tarjeta: Encuestas Realizadas -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-poll mr-3 text-indigo-500 text-3xl"></i>
                        <span>Encuestas Completadas</span>
                    </h2>
                    <button class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <p class="text-7xl font-extrabold text-indigo-500 mt-4">{{ $totalEncuestas }}</p>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center text-indigo-500">
                        <i class="fas fa-chart-line mr-1"></i>
                        95%
                    </span>
                    <span class="ml-2">tasa de respuesta</span>
                </div>
            </div>

            <!-- Tarjeta: Próximos Trabajos -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 flex items-center mb-2">
                        <i class="fas fa-tasks mr-3 text-red-500 text-3xl"></i>
                        <span>Próximos Trabajos</span>
                    </h2>
                    <button class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <p class="text-7xl font-extrabold text-red-500 mt-4">{{ $trabajosPendientes }}</p>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Progreso</span>
                        <span>65%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full mt-2">
                        <div class="h-2 bg-red-500 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Gráfica de Trabajos -->
            <div class="bg-white p-8 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                        <i class="fas fa-chart-pie mr-3 text-purple-500 text-2xl"></i>
                        <span>Trabajos por Estado</span>
                    </h2>
                    <div class="flex space-x-2">
                        <button class="text-gray-400 hover:text-gray-500 p-2">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="text-gray-400 hover:text-gray-500 p-2">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                <div class="relative" style="height: 250px;">
                    <canvas id="trabajosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sección de Actividad Reciente -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-700">Actividad Reciente</h2>
                <button class="text-indigo-600 hover:text-indigo-700 font-medium">
                    Ver todo
                </button>
            </div>
            <div class="flow-root">
                <ul class="-mb-8">
                    <li class="relative pb-8">
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-check text-white"></i>
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Trabajo completado: <span class="font-medium text-gray-900">Mantenimiento Jardín Central</span></p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time>Hace 3h</time>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="relative pb-8">
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-user-plus text-white"></i>
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Nuevo trabajador asignado: <span class="font-medium text-gray-900">Carlos Martínez</span></p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time>Hace 6h</time>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('trabajosChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($trabajosEstados->pluck('estado')) !!},
            datasets: [{
                label: 'Trabajos por Estado',
                data: {!! json_encode($trabajosEstados->pluck('count')) !!},
                backgroundColor: [
                    'rgba(76, 175, 80, 0.8)',  // Verde más suave
                    'rgba(255, 193, 7, 0.8)',  // Amarillo más suave
                    'rgba(244, 67, 54, 0.8)'   // Rojo más suave
                ],
                borderColor: [
                    'rgba(76, 175, 80, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(244, 67, 54, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                title: {
                    display: false
                }
            },
            cutout: '70%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Inicializar tooltips si estás usando Bootstrap
    if(typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
});
</script>
@endsection