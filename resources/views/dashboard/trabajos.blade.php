<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajos - RYD Jardinería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-emerald-50">
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="/img/logo.png" alt="RYD" class="h-10">
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('worker.dashboard') }}" 
                           class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('worker.trabajos') }}" 
                           class="border-emerald-500 text-emerald-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg text-white p-6 mb-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold">Gestión de Trabajos</h1>
                    <p class="text-emerald-100">Administra tus trabajos asignados</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <select class="bg-white/20 rounded-lg px-4 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <option value="">Todos los Estados</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="en_progreso">En Progreso</option>
                        <option value="completado">Completados</option>
                    </select>
                    <input type="date" class="bg-white/20 rounded-lg px-4 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/50">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-emerald-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">Trabajo</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($trabajos as $trabajo)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $trabajo->titulo }}</div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $trabajo->direccion }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $trabajo->fecha_programada ? $trabajo->fecha_programada->format('d/m/Y') : 'No programado' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $trabajo->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $trabajo->estado === 'en_progreso' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $trabajo->estado === 'completado' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                                    {{ ucfirst($trabajo->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    @if($trabajo->estado === 'pendiente')
                                        <button onclick="openModal('Iniciar', {{ $trabajo->id }})" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            <i class="fas fa-play-circle text-xl"></i>
                                        </button>
                                    @endif
                                    
                                    @if($trabajo->estado === 'en_progreso')
                                        <button onclick="openModal('Finalizar', {{ $trabajo->id }})" 
                                                class="text-emerald-600 hover:text-emerald-800 transition-colors duration-200">
                                            <i class="fas fa-check-circle text-xl"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Iniciar Trabajo -->
    <div id="modalIniciar" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <form method="POST" action="" id="formIniciar" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Iniciar Trabajo</h3>
                        <button type="button" onclick="closeModal('modalIniciar')" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-camera mr-2"></i>Foto Inicial
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                        <span>Subir foto</span>
                                        <input type="file" name="foto" accept="image/*" capture="environment" required class="sr-only">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('modalIniciar')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                            <i class="fas fa-play mr-2"></i>Iniciar Trabajo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Finalizar Trabajo -->
    <div id="modalFinalizar" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <form method="POST" action="" id="formFinalizar" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Finalizar Trabajo</h3>
                        <button type="button" onclick="closeModal('modalFinalizar')" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-camera mr-2"></i>Foto Final
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                        <span>Subir foto</span>
                                        <input type="file" name="foto" accept="image/*" capture="environment" required class="sr-only">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('modalFinalizar')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>Finalizar Trabajo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(tipo, trabajoId) {
        const modal = document.getElementById(`modal${tipo}`);
        const form = document.getElementById(`form${tipo}`);
        form.action = `/worker/trabajos/${trabajoId}/${tipo.toLowerCase()}`;
        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    </script>
</body>
</html>