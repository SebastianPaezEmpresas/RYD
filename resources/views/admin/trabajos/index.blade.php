@extends('admin.layouts.app')

@section('content')
<div class="pt-16 bg-gray-100 min-h-screen">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
       <div class="flex justify-between items-center mb-6">
           <h1 class="text-2xl font-semibold text-gray-900">Gestión de Trabajos</h1>
           <button id="openModal" class="bg-indigo-600 text-white px-4 py-2 rounded-lg inline-flex items-center hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
               <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
               </svg>
               Nuevo Trabajo
           </button>
       </div>

       <!-- Filtros -->
       <div class="bg-white rounded-lg shadow p-4 mb-6">
           <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
               <div>
                   <label class="block text-sm font-medium text-gray-700">Estado</label>
                   <select id="filtroEstado" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                       <option value="">Todos los Estados</option>
                       <option value="pendiente">Pendiente</option>
                       <option value="en_progreso">En Progreso</option>
                       <option value="completado">Completado</option>
                   </select>
               </div>
               <div>
                   <label class="block text-sm font-medium text-gray-700">Trabajador</label>
                   <select id="filtroTrabajador" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                       <option value="">Todos los Trabajadores</option>
                       @foreach($trabajadores as $trabajador)
                           <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
                       @endforeach
                   </select>
               </div>
               <div>
                   <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                   <input type="date" id="filtroFechaInicio" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
               </div>
               <div>
                   <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                   <input type="date" id="filtroFechaFin" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
               </div>
           </div>
       </div>

       <!-- Calendario -->
       <div class="bg-white rounded-lg shadow p-4">
           <div id="calendar"></div>
       </div>
   </div>
</div>

<!-- Modal Trabajo -->
<div id="trabajoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
   <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
       <div class="flex justify-between items-center mb-4">
           <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Nuevo Trabajo</h3>
           <button id="closeModal" class="text-gray-400 hover:text-gray-500">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
               </svg>
           </button>
       </div>
       
       <form id="trabajoForm" class="space-y-4">
           @csrf
           <input type="hidden" name="_method" value="POST">
           <input type="hidden" name="trabajo_id" id="trabajo_id">
           
           <div>
               <label class="block text-sm font-medium text-gray-700">Título *</label>
               <input type="text" name="titulo" id="titulo" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
           </div>

           <div>
               <label class="block text-sm font-medium text-gray-700">Descripción</label>
               <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
           </div>

           <div class="grid grid-cols-2 gap-4">
               <div>
                   <label class="block text-sm font-medium text-gray-700">Trabajador *</label>
                   <select name="trabajador_id" id="trabajador_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                       <option value="">Seleccionar trabajador</option>
                       @foreach($trabajadores as $trabajador)
                           <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
                       @endforeach
                   </select>
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700">Tipo de Trabajo</label>
                   <select name="tipo_trabajo_id" id="tipo_trabajo_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                       <option value="">Seleccionar tipo</option>
                       @foreach($tipoTrabajos as $tipo)
                           <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                       @endforeach
                   </select>
               </div>
           </div>

           <div class="grid grid-cols-2 gap-4">
               <div>
                   <label class="block text-sm font-medium text-gray-700">Estado *</label>
                   <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                       <option value="pendiente">Pendiente</option>
                       <option value="en_progreso">En Progreso</option>
                       <option value="completado">Completado</option>
                   </select>
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700">Email del Cliente</label>
                   <input type="email" name="cliente_email" id="cliente_email" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
               </div>
           </div>

           <div class="grid grid-cols-2 gap-4">
               <div>
                   <label class="block text-sm font-medium text-gray-700">Fecha Inicio *</label>
                   <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" 
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
               </div>
               <div>
                   <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                   <input type="datetime-local" name="fecha_fin" id="fecha_fin" 
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
               </div>
           </div>

           <div class="flex justify-end space-x-3 pt-4">
               <button type="button" id="btnEliminar" class="hidden px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                   Eliminar
               </button>
               <button type="button" id="cancelModal" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                   Cancelar
               </button>
               <button type="submit" id="btnGuardar"
                   class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                   Guardar
               </button>
           </div>
       </form>
   </div>
</div>
@endsection

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<style>
   .fc-view-container {
       background-color: white;
       padding: 1rem;
   }
   .fc-event {
       cursor: pointer;
   }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
   let currentTrabajoId = null;

   // Inicializar calendario
   var calendar = $('#calendar').fullCalendar({
       header: {
           left: 'prev,next today',
           center: 'title',
           right: 'month,agendaWeek,agendaDay'
       },
       locale: 'es',
       defaultView: 'month',
       navLinks: true,
       editable: true,
       selectable: true,
       selectHelper: true,
       events: @json($eventos),
       
       eventClick: function(event) {
           currentTrabajoId = event.id;
           cargarTrabajo(event.id);
       },

       eventDrop: function(event, delta, revertFunc) {
           actualizarFechas(event.id, event.start, event.end || event.start);
       },
       
       eventResize: function(event, delta, revertFunc) {
           actualizarFechas(event.id, event.start, event.end);
       },
       
       select: function(start, end) {
           openModal('nuevo');
           document.getElementById('fecha_inicio').value = start.format('YYYY-MM-DDTHH:mm');
           document.getElementById('fecha_fin').value = end.format('YYYY-MM-DDTHH:mm');
       }
   });

   async function cargarTrabajo(id) {
       try {
           const response = await fetch(`/admin/trabajos/${id}`, {
               method: 'GET',
               headers: {
                   'Accept': 'application/json',
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                   'X-Requested-With': 'XMLHttpRequest'
               }
           });

           if (!response.ok) {
               throw new Error(`Error HTTP: ${response.status}`);
           }

           const data = await response.json();

           if (data.success && data.trabajo) {
               const trabajo = data.trabajo;
               openModal('editar');
               
               // Actualizar campos del formulario
               document.getElementById('trabajo_id').value = trabajo.id;
               document.querySelector('input[name="titulo"]').value = trabajo.titulo;
               document.querySelector('textarea[name="descripcion"]').value = trabajo.descripcion || '';
               document.querySelector('select[name="trabajador_id"]').value = trabajo.trabajador_id;
               document.querySelector('select[name="tipo_trabajo_id"]').value = trabajo.tipo_trabajo_id || '';
               document.querySelector('select[name="estado"]').value = trabajo.estado;
               document.querySelector('input[name="cliente_email"]').value = trabajo.cliente_email || '';
               
               // Formatear fechas
               if (trabajo.fecha_inicio) {
                   document.getElementById('fecha_inicio').value = moment(trabajo.fecha_inicio).format('YYYY-MM-DDTHH:mm');
               }
               if (trabajo.fecha_fin) {
                   document.getElementById('fecha_fin').value = moment(trabajo.fecha_fin).format('YYYY-MM-DDTHH:mm');
               }

               document.getElementById('btnEliminar').classList.remove('hidden');
           } else {
               throw new Error(data.message || 'No se pudo cargar la información del trabajo');
           }
       } catch (error) {
           console.error('Error:', error);
           alert('Error al cargar el trabajo: ' + error.message);
       }
   }

   async function actualizarFechas(id, start, end) {
       try {
           const response = await fetch(`/admin/trabajos/${id}/fechas`, {
               method: 'PATCH',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                   'Accept': 'application/json'
               },
               body: JSON.stringify({
                   fecha_inicio: start.format('YYYY-MM-DD HH:mm:ss'),
                   fecha_fin: end ? end.format('YYYY-MM-DD HH:mm:ss') : null
               })
           });

           const result = await response.json();
           
           if (!result.success) {
               calendar.fullCalendar('refetchEvents');
               throw new Error(result.message || 'Error al actualizar las fechas');
           }
       } catch (error) {
           console.error('Error:', error);
           alert('Error al actualizar las fechas: ' + error.message);
           calendar.fullCalendar('refetchEvents');
       }
   }

   // Modal handling
   const modal = document.getElementById('trabajoModal');
   const modalTitle = document.getElementById('modalTitle');
   const openModalBtn = document.getElementById('openModal');
   const closeModalBtn = document.getElementById('closeModal');
   const cancelModalBtn = document.getElementById('cancelModal');
   const btnEliminar = document.getElementById('btnEliminar');
   const trabajoForm = document.getElementById('trabajoForm');

   function openModal(tipo) {
       modal.classList.remove('hidden');
       if (tipo === 'editar') {
           modalTitle.textContent = 'Editar Trabajo';
           btnEliminar.classList.remove('hidden');
           trabajoForm._method.value = 'PUT';
       } else {
           modalTitle.textContent = 'Nuevo Trabajo';
           btnEliminar.classList.add('hidden');
           trabajoForm._method.value = 'POST';
           currentTrabajoId = null;
           trabajoForm.reset();
       }
   }

   function closeModal() {
       modal.classList.add('hidden');
       trabajoForm.reset();
       currentTrabajoId = null;
       btnEliminar.classList.add('hidden');
   }

   openModalBtn.addEventListener('click', () => openModal('nuevo'));
   closeModalBtn.addEventListener('click', closeModal);
   cancelModalBtn.addEventListener('click', closeModal);

   // Eliminar trabajo
   btnEliminar.addEventListener('click', async function() {
       if (!currentTrabajoId) return;
       
       if (confirm('¿Está seguro de que desea eliminar este trabajo?')) {
           try {
               const response = await fetch(`/admin/trabajos/${currentTrabajoId}`, {
                   method: 'DELETE',
                   headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                       'Accept': 'application/json'
                   }
               });

               const result = await response.json();
               
               if (result.success) {
                   calendar.fullCalendar('removeEvents', currentTrabajoId);
                   closeModal();
                   alert('Trabajo eliminado con éxito');
               } else {
                   throw new Error(result.message || 'Error al eliminar el trabajo');
               }
           } catch (error) {
               console.error('Error:', error);
               alert('Error al eliminar el trabajo: ' + error.message);
           }
       }
   });

   // Form submission
   trabajoForm.addEventListener('submit', async function(e) {
       e.preventDefault();
       const formData = new FormData(this);
       const method = formData.get('_method').toUpperCase();
       const url = method === 'PUT' ? `/admin/trabajos/${currentTrabajoId}` : '/admin/trabajos';
       
       try {
           const response = await fetch(url, {
               method: method === 'PUT' ? 'POST' : method,
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                   'Accept': 'application/json',
                   'X-Requested-With': 'XMLHttpRequest'
               }
           });

           const result = await response.json();

           if (result.success) {
               closeModal();
               if (method === 'PUT') {
                   calendar.fullCalendar('removeEvents', currentTrabajoId);
               }
               calendar.fullCalendar('renderEvent', result.evento, true);
               alert(method === 'PUT' ? 'Trabajo actualizado con éxito' : 'Trabajo creado con éxito');
           } else {
               throw new Error(result.message || 'Error al procesar el trabajo');
           }
       } catch (error) {
           console.error('Error:', error);
           alert('Error al procesar el trabajo: ' + error.message);
       }
   });

   // Filtros
   const filtroEstado = document.getElementById('filtroEstado');
   const filtroTrabajador = document.getElementById('filtroTrabajador');
   const filtroFechaInicio = document.getElementById('filtroFechaInicio');
   const filtroFechaFin = document.getElementById('filtroFechaFin');

   async function aplicarFiltros() {
       const params = new URLSearchParams({
           estado: filtroEstado.value,
           trabajador_id: filtroTrabajador.value,
           fecha_inicio: filtroFechaInicio.value,
           fecha_fin: filtroFechaFin.value
       });

       try {
           const response = await fetch(`/admin/trabajos/filtrar?${params}`);
           const eventos = await response.json();
           
           calendar.fullCalendar('removeEvents');
           calendar.fullCalendar('addEventSource', eventos);
       } catch (error) {
           console.error('Error:', error);
           alert('Error al filtrar los trabajos');
       }
   }

   filtroEstado.addEventListener('change', aplicarFiltros);
   filtroTrabajador.addEventListener('change', aplicarFiltros);
   filtroFechaInicio.addEventListener('change', aplicarFiltros);
   filtroFechaFin.addEventListener('change', aplicarFiltros);

   function getEventColor(estado) {
       const colors = {
           'pendiente': '#EAB308',
           'en_progreso': '#3B82F6', 
           'completado': '#22C55E'
       };
       return colors[estado] || '#6B7280';
   }
});
</script>
@endpush