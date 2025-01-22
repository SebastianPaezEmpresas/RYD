@extends('admin.layouts.app')

@section('content')
    <!-- Encabezado -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Gestión de Encuestas</h1>
            <div class="flex gap-2">
                <button onclick="openNuevaEncuestaModal()" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Encuesta
                </button>
                <a href="{{ route('admin.encuestas.estadisticas') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-chart-line mr-2"></i>
                    Ver Estadísticas
                </a>
                <button id="exportarEncuestas" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                    <i class="fas fa-download mr-2"></i>
                    Exportar Datos
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Encuesta -->
    <div id="modalNuevaEncuesta" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Nueva Encuesta</h3>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <label for="trabajo_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccione el Trabajo
                        </label>
                        <select id="trabajo_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Seleccione un trabajo...</option>
                        </select>
                    </div>
                    <div id="detallesTrabajo" class="mb-4 p-4 bg-gray-50 rounded-md hidden">
                        <h4 class="font-medium text-gray-900 mb-2">Detalles del Trabajo</h4>
                        <p class="text-sm text-gray-600 mb-1" id="descripcionTrabajo"></p>
                        <p class="text-sm text-gray-600 mb-1" id="clienteTrabajo"></p>
                        <p class="text-sm text-gray-600" id="fechaTrabajo"></p>
                        <p class="text-sm text-gray-600" id="emailCliente"></p>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeNuevaEncuestaModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="crearEncuesta()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Crear Encuesta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resto del código existente sin cambios -->
    @include('admin.encuestas.partials.filtros')
    @include('admin.encuestas.partials.tabla')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Scripts existentes
    $('.enviar-encuesta').click(function() {
        const id = $(this).data('id');
        if(confirm('¿Está seguro que desea reenviar esta encuesta?')) {
            $.post(`${id}/reenviar`)
                .done(function(response) {
                    alert('Encuesta reenviada correctamente');
                })
                .fail(function(error) {
                    alert('Error al reenviar la encuesta');
                });
        }
    });

    $('.eliminar-encuesta').click(function() {
        const id = $(this).data('id');
        if(confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
            $.ajax({
                url: id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    alert('Error al eliminar la encuesta');
                }
            });
        }
    });

    $('#exportarEncuestas').click(function(e) {
        e.preventDefault();
        const queryString = $('#filtrosForm').serialize();
        window.location.href = `{{ route('admin.encuestas.exportar') }}?${queryString}`;
    });
});

// Funciones del modal
function openNuevaEncuestaModal() {
    // Cargar trabajos al abrir el modal
    fetch('{{ route('admin.trabajos.filtrar') }}?estado=completado')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('trabajo_id');
            select.innerHTML = '<option value="">Seleccione un trabajo...</option>';
            data.forEach(trabajo => {
                select.innerHTML += `<option value="${trabajo.id}">${trabajo.descripcion} - ${trabajo.cliente.nombre}</option>`;
            });
        });

    document.getElementById('modalNuevaEncuesta').classList.remove('hidden');
}

function closeNuevaEncuestaModal() {
    document.getElementById('modalNuevaEncuesta').classList.add('hidden');
    document.getElementById('detallesTrabajo').classList.add('hidden');
    document.getElementById('trabajo_id').value = '';
}

function crearEncuesta() {
    const trabajo_id = document.getElementById('trabajo_id').value;
    
    if (!trabajo_id) {
        alert('Por favor seleccione un trabajo');
        return;
    }

    fetch('{{ route('admin.encuestas.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ trabajo_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Encuesta creada con éxito');
            location.reload();
        } else {
            alert(data.message || 'Error al crear la encuesta');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al crear la encuesta');
    });
}

// Mostrar detalles del trabajo al seleccionarlo
document.getElementById('trabajo_id').addEventListener('change', function() {
    const detallesTrabajo = document.getElementById('detallesTrabajo');
    if (this.value) {
        fetch(`/admin/trabajos/${this.value}`)
            .then(response => response.json())
            .then(trabajo => {
                document.getElementById('descripcionTrabajo').textContent = `Descripción: ${trabajo.descripcion}`;
                document.getElementById('clienteTrabajo').textContent = `Cliente: ${trabajo.cliente.nombre}`;
                document.getElementById('fechaTrabajo').textContent = `Fecha: ${trabajo.fecha_realizacion}`;
                document.getElementById('emailCliente').textContent = `Email: ${trabajo.cliente_email || 'No especificado'}`;
                detallesTrabajo.classList.remove('hidden');
            });
    } else {
        detallesTrabajo.classList.add('hidden');
    }
});
</script>
@endpush