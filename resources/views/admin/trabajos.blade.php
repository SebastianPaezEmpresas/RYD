@extends('admin.layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/filepond/dist/filepond.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="bg-white shadow-lg rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Trabajos</h1>
        <button 
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center gap-2"
            data-modal-toggle="createTrabajoModal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Trabajo
        </button>
    </div>

    <!-- Vista de Calendario y Lista -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calendario -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <div id="calendar"></div>
        </div>

        <!-- Lista de Próximos Trabajos -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Próximos Trabajos</h3>
            <div class="space-y-4">
                @forelse($proximosTrabajos as $trabajo)
                    <div class="border-l-4 {{ $trabajo->estado_color }} bg-gray-50 p-4 rounded-r-lg">
                        <h4 class="font-semibold text-gray-800">{{ $trabajo->titulo }}</h4>
                        <p class="text-sm text-gray-600">{{ $trabajo->fecha_inicio->format('d/m/Y H:i') }}</p>
                        <span class="inline-block px-2 py-1 text-xs rounded-full {{ $trabajo->estado_bg_color }} mt-2">
                            {{ $trabajo->estado }}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">
                            Asignado a: {{ $trabajo->trabajador->name }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        No hay trabajos próximos programados
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal para crear trabajo -->
    <div id="createTrabajoModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form action="{{ route('admin.trabajos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Tipo de Trabajo -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Trabajo</label>
                            <select name="tipo_trabajo_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                <select name="estado" id="estado" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_progreso">En Progreso</option>
                                    <option value="completado">Completado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Inicio</label>
                                <input type="text" name="fecha_inicio" id="fecha_inicio" required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Fin</label>
                                <input type="text" name="fecha_fin" id="fecha_fin" required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción del Trabajo</label>
                            <textarea name="descripcion" id="descripcion" rows="4" required 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- Asignación de Trabajador -->
                        <div class="mb-6">
                            <label for="trabajador_id" class="block text-sm font-medium text-gray-700 mb-2">Asignar Trabajador</label>
                            <select name="trabajador_id" id="trabajador_id" required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($trabajadores as $trabajador)
                                <option value="{{ $trabajador->id }}">
                                    {{ $trabajador->name }} - {{ $trabajador->especialidad }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Email del Cliente -->
                        <div class="mb-6">
                            <label for="cliente_email" class="block text-sm font-medium text-gray-700 mb-2">Email del Cliente</label>
                            <input type="email" name="cliente_email" id="cliente_email"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="cliente@ejemplo.com">
                            <p class="mt-1 text-sm text-gray-500">
                                Se enviará un enlace al cliente para evaluar el trabajo una vez completado
                            </p>
                        </div>

                        <!-- Evidencias -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evidencias Iniciales</label>
                            <input type="file" class="filepond" name="evidencias[]" multiple data-max-files="5" 
                                   accept="image/*,.pdf,.doc,.docx">
                            <p class="mt-1 text-sm text-gray-500">
                                Sube fotos o documentos relacionados con el trabajo (máx. 5 archivos)
                            </p>
                        </div>
                    </div>

                    <!-- Footer del Modal -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Crear Trabajo
                        </button>
                        <button type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                onclick="closeModal()">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond/dist/filepond.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización del calendario
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($trabajosCalendario),
        eventClick: function(info) {
            window.location.href = `/admin/trabajos/${info.event.id}`;
        },
        eventDidMount: function(info) {
            tippy(info.el, {
                content: `
                    <div class="p-2">
                        <strong>${info.event.title}</strong><br>
                        ${info.event.extendedProps.descripcion}<br>
                        <span class="text-sm">Asignado a: ${info.event.extendedProps.trabajador}</span>
                    </div>
                `,
                allowHTML: true,
            });
        }
    });
    calendar.render();

    // Inicialización de FilePond
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    const inputElement = document.querySelector('input[type="file"].filepond');
    const pond = FilePond.create(inputElement, {
        labelIdle: 'Arrastra y suelta tus archivos o <span class="filepond--label-action">Examinar</span>',
        maxFileSize: '3MB',
        maxFiles: 5,
        acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        server: {
            url: '/api/upload-evidencia',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    // Inicialización de Flatpickr
    flatpickr("#fecha_inicio", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        locale: "es"
    });

    flatpickr("#fecha_fin", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        locale: "es"
    });

    // Funciones del modal
    window.closeModal = function() {
        document.getElementById('createTrabajoModal').classList.add('hidden');
    }

    document.querySelector('[data-modal-toggle="createTrabajoModal"]').addEventListener('click', function() {
        document.getElementById('createTrabajoModal').classList.remove('hidden');
    });
});
</script>
@endsection-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($tiposTrabajos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Información Básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                                <input type="text" name="titulo" id="titulo" required 
                                       class="w-full rounded-md border