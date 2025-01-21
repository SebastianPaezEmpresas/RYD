@extends('layouts.cliente')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Detalles del Trabajo -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="relative">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="absolute bottom-0 left-0 right-0 px-6 py-3 bg-black bg-opacity-40">
                    <h1 class="text-3xl font-bold text-white">{{ $trabajo->titulo }}</h1>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <!-- Estado del Trabajo -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full {{ $trabajo->estado_bg_color }}">
                            {{ ucfirst($trabajo->estado) }}
                        </span>
                        <span class="text-gray-500">
                            {{ $trabajo->fecha_inicio->format('d/m/Y H:i') }} - {{ $trabajo->fecha_fin->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descripción del Trabajo</h3>
                    <p class="text-gray-600">{{ $trabajo->descripcion }}</p>
                </div>

                <!-- Información del Trabajador -->
                <div class="mb-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Trabajador Asignado</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full" src="{{ $trabajo->trabajador->profile_photo_url }}" alt="">
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $trabajo->trabajador->name }}</p>
                            <p class="text-gray-500">{{ $trabajo->trabajador->especialidad }}</p>
                        </div>
                    </div>
                </div>

                <!-- Evidencias -->
                @if($trabajo->evidencias_inicio || $trabajo->evidencias_fin)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Evidencias del Trabajo</h3>
                    
                    <!-- Evidencias Iniciales -->
                    @if($trabajo->evidencias_inicio)
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-600 mb-2">Inicio del Trabajo</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($trabajo->evidencias_inicio as $evidencia)
                            <div class="relative group">
                                @if(Str::startsWith($evidencia['tipo'], 'image/'))
                                    <img src="{{ Storage::url($evidencia['path']) }}" 
                                         alt="Evidencia" 
                                         class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <a href="{{ Storage::url($evidencia['path']) }}" 
                                   target="_blank"
                                   class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                    <span class="text-white font-medium">Ver</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Evaluación del Servicio -->
                @if($trabajo->estado === 'completado' && !$trabajo->calificacion)
                <div class="mt-8 border-t pt-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Evalúa el Servicio</h3>
                    <form action="{{ route('cliente.trabajo.calificar', $trabajo->link_compartido) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Sistema de Estrellas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Calificación</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="star-rating text-2xl focus:outline-none" 
                                        data-rating="{{ $i }}"
                                        onclick="setRating({{ $i }})">
                                    ★
                                </button>
                                @endfor
                            </div>
                            <input type="hidden" name="calificacion" id="calificacion" required>
                        </div>

                        <!-- Comentario -->
                        <div>
                            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">
                                Comentarios sobre el servicio
                            </label>
                            <textarea name="comentario" 
                                      id="comentario" 
                                      rows="4" 
                                      required
                                      class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Cuéntanos tu experiencia con el servicio..."></textarea>
                        </div>

                        <!-- Botón de Envío -->
                        <div>
                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enviar Evaluación
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Calificación Existente -->
                @if($trabajo->calificacion)
                <div class="mt-8 border-t pt-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Tu Evaluación</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <span class="text-2xl {{ $i <= $trabajo->calificacion ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <p class="text-gray-600">{{ $trabajo->comentario_cliente }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function setRating(rating) {
    document.getElementById('calificacion').value = rating;
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < rating);
        star.classList.toggle('text-gray-300', index >= rating);
    });
}

// Inicializar tooltips para las evidencias
document.addEventListener('DOMContentLoaded', function() {
    tippy('[data-tippy-content]');
});
</script>
@endsection48 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <a href="{{ Storage::url($evidencia['path']) }}" 
                                   target="_blank"
                                   class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                    <span class="text-white font-medium">Ver</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Evidencias Finales -->
                    @if($trabajo->evidencias_fin)
                    <div>
                        <h4 class="text-md font-medium text-gray-600 mb-2">Finalización del Trabajo</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($trabajo->evidencias_fin as $evidencia)
                            <div class="relative group">
                                @if(Str::startsWith($evidencia['tipo'], 'image/'))
                                    <img src="{{ Storage::url($evidencia['path']) }}" 
                                         alt="Evidencia" 
                                         class="w-full h-48 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-