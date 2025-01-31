<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Trabajador - RYD Jardinería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-emerald-50">
    <nav class="bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="/img/logo.png" alt="RYD" class="h-10">
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('worker.dashboard') }}" 
                           class="border-emerald-500 text-emerald-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('worker.trabajos') }}" 
                           class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-briefcase mr-2"></i>Trabajos
                        </a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @php
        $trabajosPendientes = auth()->user()->trabajos()->where('estado', 'pendiente')->count();
        $trabajosCompletados = auth()->user()->trabajos()->where('estado', 'completado')->count();
        $satisfaccion = auth()->user()->trabajos()
            ->whereHas('encuesta')
            ->join('encuestas', 'trabajos.id', '=', 'encuestas.trabajo_id')
            ->avg('encuestas.calificacion') ?? 0;
        $proximosTrabajos = auth()->user()->trabajos()
            ->where('estado', 'pendiente')
            ->orderBy('fecha_programada', 'asc')
            ->take(5)
            ->get();
        $actividadReciente = auth()->user()->trabajos()
            ->where('estado', 'completado')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        @endphp

        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg text-white p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">¡Bienvenido, {{ auth()->user()->name }}!</h1>
                    <p class="text-emerald-100">Dashboard RYD Jardinería</p>
                </div>
                <div class="flex space-x-4">
                    <select class="bg-white/20 rounded-lg px-4 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <option>Todos los Estados</option>
                        <option>Pendientes</option>
                        <option>En Progreso</option>
                        <option>Completados</option>
                    </select>
                    <input type="date" class="bg-white/20 rounded-lg px-4 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/50">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="bg-emerald-100 p-4 rounded-xl">
                        <i class="fas fa-tasks text-2xl text-emerald-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-3xl font-bold text-gray-800">{{ $trabajosPendientes }}</h3>
                        <p class="text-gray-500">Trabajos Pendientes</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="bg-green-100 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-3xl font-bold text-gray-800">{{ $trabajosCompletados }}</h3>
                        <p class="text-gray-500">Trabajos Completados</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-4 rounded-xl">
                        <i class="fas fa-star text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-3xl font-bold text-gray-800">{{ number_format($satisfaccion, 1) }}</h3>
                        <p class="text-gray-500">Satisfacción Promedio</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar mr-2 text-emerald-500"></i>
                        Próximos Trabajos
                    </h2>
                    <div class="space-y-4">
                        @forelse($proximosTrabajos as $trabajo)
                            <div class="bg-emerald-50 rounded-lg p-4 hover:bg-emerald-100 transition-colors duration-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="font-medium text-gray-800">{{ $trabajo->titulo }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $trabajo->direccion }}
                                        </p>
                                    </div>
                                    <span class="text-sm bg-emerald-200 text-emerald-800 px-3 py-1 rounded-full">
                                        {{ \Carbon\Carbon::parse($trabajo->fecha)->format('d M') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">No hay trabajos próximos</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-emerald-500"></i>
                        Actividad Reciente
                    </h2>
                    <div class="space-y-4">
                        @forelse($actividadReciente as $actividad)
                            <div class="flex items-center p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                <div class="bg-emerald-100 p-2 rounded-full">
                                    <i class="fas fa-check text-emerald-600"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $actividad->titulo }}</p>
                                    <p class="text-xs text-gray-500">{{ $actividad->updated_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-emerald-600 font-medium">Completado</span>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">No hay actividad reciente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>