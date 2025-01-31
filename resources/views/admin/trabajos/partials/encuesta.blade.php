{{-- admin/trabajos/partials/encuesta.blade.php --}}
<div class="border-t">
    <div class="p-6">
        <h3 class="text-lg font-medium text-emerald-800 mb-4">Encuesta del Cliente</h3>
        
        <div class="space-y-6">
            <div class="bg-emerald-50 p-4 rounded-lg">
                <h4 class="text-sm text-emerald-600 font-medium mb-2">Calificación</h4>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-emerald-600">{{ $encuesta->calificacion }}</span>
                    <div class="flex text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $encuesta->calificacion ? '' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg">
                <h4 class="text-sm text-emerald-600 font-medium mb-2">Comentario</h4>
                <p class="text-gray-700">{{ $encuesta->comentario }}</p>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg">
                <h4 class="text-sm text-emerald-600 font-medium mb-2">Sugerencias</h4>
                <p class="text-gray-700">{{ $encuesta->sugerencias }}</p>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg">
                <h4 class="text-sm text-emerald-600 font-medium mb-2">¿Recomendaría nuestros servicios?</h4>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $encuesta->recomendaria ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $encuesta->recomendaria ? '¡Sí!' : 'No' }}
                </span>
            </div>
        </div>
    </div>
</div>