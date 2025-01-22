// Configuración inicial del calendario
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        selectable: true,
        editable: true,
        events: window.eventos || [],
        dateClick: function(info) {
            openNuevoTrabajoModal(info.date);
        },
        eventClick: function(info) {
            cargarTrabajo(info.event.id);
        },
        eventDrop: function(info) {
            actualizarFechasTrabajo(info.event);
        },
        eventResize: function(info) {
            actualizarFechasTrabajo(info.event);
        }
    });

    calendar.render();

    // Event listeners para filtros
    document.querySelectorAll('#filtrosForm select, #filtrosForm input').forEach(elemento => {
        elemento.addEventListener('change', function() {
            filtrarTrabajos();
        });
    });

    // Event listener para el botón nuevo trabajo
    document.getElementById('btnNuevoTrabajo').addEventListener('click', function() {
        openNuevoTrabajoModal();
    });
});

// Función para cargar trabajo
function cargarTrabajo(id) {
    fetch(`/admin/trabajos/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar el trabajo');
            return response.json();
        })
        .then(data => {
            if (data.trabajo) {
                const trabajo = data.trabajo;
                document.getElementById('editTitulo').value = trabajo.titulo;
                document.getElementById('editDescripcion').value = trabajo.descripcion || '';
                document.getElementById('editTrabajador').value = trabajo.trabajador_id;
                document.getElementById('editTipoTrabajo').value = trabajo.tipo_trabajo_id || '';
                document.getElementById('editEstado').value = trabajo.estado;
                document.getElementById('editClienteEmail').value = trabajo.cliente_email || '';
                document.getElementById('editNotasFinales').value = trabajo.notas_finales || '';
                
                if (trabajo.fecha_inicio) {
                    document.getElementById('editFechaInicio').value = trabajo.fecha_inicio.split('T')[0];
                }
                if (trabajo.fecha_fin) {
                    document.getElementById('editFechaFin').value = trabajo.fecha_fin.split('T')[0];
                }
                
                document.getElementById('trabajoId').value = trabajo.id;
                
                // Mostrar modal de edición
                const editModal = new bootstrap.Modal(document.getElementById('editTrabajoModal'));
                editModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el trabajo');
        });
}

// Función para actualizar fechas de trabajo
function actualizarFechasTrabajo(event) {
    const id = event.id;
    const fechaInicio = event.start.toISOString();
    const fechaFin = event.end ? event.end.toISOString() : null;

    fetch(`/admin/trabajos/${id}/fechas`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'Error al actualizar fechas');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        calendar.refetchEvents();
        alert('Error al actualizar las fechas del trabajo');
    });
}

// Función para filtrar trabajos
function filtrarTrabajos() {
    const filtros = {
        estado: document.getElementById('estado').value,
        trabajador_id: document.getElementById('trabajador').value,
        fecha_inicio: document.getElementById('fecha_inicio').value,
        fecha_fin: document.getElementById('fecha_fin').value
    };

    const queryString = new URLSearchParams(filtros).toString();

    fetch(`/admin/trabajos/filtrar?${queryString}`)
        .then(response => response.json())
        .then(events => {
            calendar.removeAllEvents();
            calendar.addEventSource(events);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al filtrar los trabajos');
        });
}

// Función para abrir modal de nuevo trabajo
function openNuevoTrabajoModal(fecha = null) {
    if (fecha) {
        document.getElementById('fecha_inicio').value = fecha.toISOString().split('T')[0];
    }
    
    // Limpiar formulario
    document.getElementById('nuevoTrabajoForm').reset();
    
    const modal = new bootstrap.Modal(document.getElementById('nuevoTrabajoModal'));
    modal.show();
}

// Función para guardar trabajo
function guardarTrabajo(formData, esNuevo = true) {
    const url = esNuevo ? '/admin/trabajos' : `/admin/trabajos/${formData.get('id')}`;
    const method = esNuevo ? 'POST' : 'PUT';

    if (!esNuevo) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (esNuevo) {
                calendar.addEvent(data.evento);
            } else {
                const evento = calendar.getEventById(formData.get('id'));
                if (evento) {
                    evento.remove();
                }
                calendar.addEvent(data.evento);
            }
            
            if (esNuevo) {
                bootstrap.Modal.getInstance(document.getElementById('nuevoTrabajoModal')).hide();
            } else {
                bootstrap.Modal.getInstance(document.getElementById('editTrabajoModal')).hide();
            }
        } else {
            throw new Error(data.message || 'Error al procesar el trabajo');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el trabajo');
    });
}

// Event listeners para formularios
document.getElementById('nuevoTrabajoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    guardarTrabajo(formData, true);
});

document.getElementById('editTrabajoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    guardarTrabajo(formData, false);
});

// Función para eliminar trabajo
function eliminarTrabajo(id) {
    if (confirm('¿Está seguro de que desea eliminar este trabajo?')) {
        fetch(`/admin/trabajos/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const evento = calendar.getEventById(id);
                if (evento) {
                    evento.remove();
                }
                bootstrap.Modal.getInstance(document.getElementById('editTrabajoModal')).hide();
            } else {
                throw new Error(data.message || 'Error al eliminar el trabajo');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el trabajo');
        });
    }
}