@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
    <div class="max-w-3xl mx-auto pt-8 px-4">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-emerald-500 p-4">
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-calendar"></i> 
                    Programar Trabajos por Conjunto
                </h1>
            </div>

            <form id="scheduleForm" action="{{ route('admin.trabajos.schedule.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre del Conjunto</label>
                        <input type="text" name="conjunto_nombre" required 
                               class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dirección del Conjunto</label>
                        <input type="text" name="conjunto_direccion" required 
                               class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo de Contacto</label>
                        <input type="email" name="conjunto_email" required 
                               class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Presupuesto por Casa</label>
                        <div class="mt-1 relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="presupuesto_casa" required min="0" 
                                   class="pl-7 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Trabajador Asignado</label>
                        <select name="worker_id" required 
                                class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Trabajo</label>
                        <select name="tipo_trabajo" required 
                                class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Poda">Poda</option>
                            <option value="Jardinería">Jardinería</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción del Trabajo</label>
                    <textarea name="descripcion" rows="3" required
                              class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200"></textarea>
                </div>

                <!-- Programación de Días -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Programación de Días</h3>
                    
                    <div class="bg-emerald-50 rounded-lg p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required
                                   class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required
                                   class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Casa Inicial</label>
                                <input type="number" name="casa_inicial" required min="1"
                                       class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Casa Final</label>
                                <input type="number" name="casa_final" required min="1"
                                       class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Casas por Día</label>
                            <input type="number" name="casas_por_dia" required min="1"
                                   class="mt-1 w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="window.history.back()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
                        Programar Trabajos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const fechaInicio = new Date(document.querySelector('input[name="fecha_inicio"]').value);
    const fechaFin = new Date(document.querySelector('input[name="fecha_fin"]').value);
    const casaInicial = parseInt(document.querySelector('input[name="casa_inicial"]').value);
    const casaFinal = parseInt(document.querySelector('input[name="casa_final"]').value);
    const casasPorDia = parseInt(document.querySelector('input[name="casas_por_dia"]').value);

    if (fechaFin < fechaInicio) {
        e.preventDefault();
        alert('La fecha final debe ser posterior a la fecha inicial');
        return;
    }

    if (casaFinal < casaInicial) {
        e.preventDefault();
        alert('La casa final debe ser mayor que la casa inicial');
        return;
    }

    const totalCasas = casaFinal - casaInicial + 1;
    const diasNecesarios = Math.ceil(totalCasas / casasPorDia);
    const diasDisponibles = Math.ceil((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24)) + 1;

    if (diasNecesarios > diasDisponibles) {
        e.preventDefault();
        alert(`Con ${casasPorDia} casas por día, necesitas al menos ${diasNecesarios} días para completar el trabajo`);
        return;
    }
});
</script>
@endpush
@endsection