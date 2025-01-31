@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <div class="max-w-4xl mx-auto pt-8 px-4">
        <!-- Header -->
        <div class="bg-emerald-500 rounded-t-xl p-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">{{ $trabajo->titulo }}</h1>
                <div class="flex items-center gap-4">
                    <span class="px-4 py-2 bg-white rounded-lg text-sm font-medium
                        @if($trabajo->estado == 'pendiente') text-yellow-600
                        @elseif($trabajo->estado == 'en_progreso') text-blue-600  
                        @else text-emerald-600 @endif">
                        {{ ucfirst($trabajo->estado) }}
                    </span>
                    <a href="{{ route('admin.trabajos.edit', $trabajo) }}" 
                        class="bg-white/10 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-xl shadow-lg">
            <!-- Detalles -->
            <div class="p-6 border-b">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Cliente</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->cliente }}</p>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Dirección</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->direccion }}</p>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Fecha</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->fecha_programada }}</p>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Tipo</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->tipo_trabajo }}</p>
                    </div>
                    <div class="md:col-span-2 bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Descripción</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->descripcion }}</p>
                    </div>
                    @if($trabajo->notas_internas)
                    <div class="md:col-span-2 bg-emerald-50 p-4 rounded-lg">
                        <h3 class="text-sm text-emerald-600 font-medium">Notas Internas</h3>
                        <p class="mt-1 font-medium text-gray-700">{{ $trabajo->notas_internas }}</p>
                    </div>
                    @endif
                </div>

                @if($trabajo->estado === 'completado')
                <div class="mt-6 p-4 bg-emerald-50 rounded-lg">
                    <h3 class="text-sm font-medium text-emerald-600 mb-2">URL de Encuesta</h3>
                    <div class="flex gap-2">
                        <input type="text" value="{{ $encuestaUrl }}" 
                               class="flex-1 bg-white p-2 rounded border border-emerald-200" readonly>
                        <button onclick="navigator.clipboard.writeText('{{ $encuestaUrl }}')"
                                class="bg-emerald-500 text-white px-4 rounded hover:bg-emerald-600">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Evidencias -->
            <div class="p-6">
                <h3 class="text-lg font-medium text-emerald-800 mb-4">Evidencias Fotográficas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($trabajo->fotos->sortBy('created_at') as $foto)
                        <div class="bg-emerald-50 rounded-lg overflow-hidden shadow-sm">
                            <div class="aspect-video">
                                <img src="{{ asset('storage/' . $foto->ruta) }}" 
                                     alt="Evidencia {{ $loop->iteration }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-emerald-600">
                                    <span class="font-medium">{{ ucfirst($foto->etapa) }}</span> 
                                    - {{ $foto->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-emerald-600">No hay evidencias fotográficas disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($trabajo->encuesta)
                @include('admin.trabajos.partials.encuesta', ['encuesta' => $trabajo->encuesta])
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
        this.src = '/img/no-image.jpg';
    });
});
</script>
@endpush
@endsection