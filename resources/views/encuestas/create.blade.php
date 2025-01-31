@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-emerald-500 px-6 py-4">
            <h2 class="text-2xl font-bold text-white text-center">
                Evalúe nuestro servicio
            </h2>
            <p class="mt-2 text-emerald-100 text-center">
                Para {{ $trabajo->tipo_trabajo }} realizado el {{ $trabajo->fecha_fin?->format('d/m/Y') }}
            </p>
        </div>

        <form action="{{ route('encuestas.store', $trabajo->encuesta_token) }}" method="POST" class="p-6 space-y-8">
            @csrf
            
            <div>
                <label class="text-lg font-medium text-gray-700 block text-center mb-6">
                    ¿Cómo calificaría nuestro servicio?
                </label>
                <div class="flex justify-center gap-6">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="calificacion" value="{{ $i }}" 
                                   class="sr-only peer" required>
                            <div class="w-16 h-16 flex items-center justify-center rounded-lg
                                      bg-white border-2 border-emerald-200
                                      peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                      hover:border-emerald-300 transition-all duration-200">
                                <span class="text-3xl">⭐</span>
                            </div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-sm font-medium text-gray-600">
                                {{ $i }}
                            </span>
                        </label>
                    @endfor
                </div>
            </div>

            <div class="space-y-6 pt-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sus comentarios nos ayudan a mejorar
                    </label>
                    <textarea name="comentario" rows="3" 
                        class="w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ¿Tiene alguna sugerencia para mejorar nuestro servicio?
                    </label>
                    <textarea name="sugerencias" rows="3" 
                        class="w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                </div>

                <div class="bg-emerald-50 rounded-lg p-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="recomendaria" value="1" 
                               class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-3 text-base text-gray-700">
                            ¿Recomendaría nuestros servicios?
                        </span>
                    </label>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" 
                    class="w-full bg-emerald-500 text-white py-3 px-4 rounded-lg text-lg font-medium
                           hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
                           transition-colors duration-200">
                    Enviar Evaluación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection