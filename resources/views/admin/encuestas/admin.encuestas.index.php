@extends('admin.layouts.app')

@section('title', 'Gestión de Encuestas')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Encuestas</h1>
        <div>
            <a href="{{ route('admin.encuestas.estadisticas') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-chart-line fa-sm text-white-50"></i> Ver Estadísticas
            </a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm ml-2" id="exportarEncuestas">
                <i class="fas fa-download fa-sm text-white-50"></i> Exportar Datos
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.encuestas.index') }}" method="GET" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="enviada" {{ request('estado') == 'enviada' ? 'selected' : '' }}>Enviada</option>
                                <option value="respondida" {{ request('estado') == 'respondida' ? 'selected' : '' }}>Respondida</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_hasta">Fecha Hasta</label>
                            <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                            <input type="text" class="form-control" name="cliente" id="cliente" value="{{ request('cliente') }}" placeholder="Email del cliente...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-sm"></i> Buscar
                        </button>
                        <a href="{{ route('admin.encuestas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo fa-sm"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Encuestas -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="encuestasTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trabajo</th>
                            <th>Email Cliente</th>
                            <th>Fecha Envío</th>
                            <th>Estado</th>
                            <th>Calificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encuestas as $encuesta)
                            <tr>
                                <td>{{ $encuesta->id }}</td>
                                <td>
                                    @if($encuesta->trabajo)
                                        <a href="{{ route('admin.trabajos.show', $encuesta->trabajo_id) }}" class="text-primary">
                                            {{ Str::limit($encuesta->trabajo->titulo ?? 'Sin título', 50) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Trabajo no disponible</span>
                                    @endif
                                </td>
                                <td>{{ $encuesta->trabajo?->cliente_email ?? 'N/A' }}</td>
                                <td>{{ $encuesta->fecha_envio ? $encuesta->fecha_envio->format('d/m/Y H:i') : 'No enviada' }}</td>
                                <td>
                                    @switch($encuesta->estado)
                                        @case('pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                            @break
                                        @case('enviada')
                                            <span class="badge badge-info">Enviada</span>
                                            @break
                                        @case('respondida')
                                            <span class="badge badge-success">Respondida</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $encuesta->estado }}</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if($encuesta->estado === 'respondida')
                                        {{ number_format($encuesta->promedio_calificaciones, 1) }}/5
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.encuestas.show', $encuesta) }}" 
                                       class="btn btn-primary btn-sm"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($encuesta->estado !== 'respondida')
                                        <button type="button" 
                                                class="btn btn-info btn-sm enviar-encuesta" 
                                                data-id="{{ $encuesta->id }}"
                                                data-email="{{ $encuesta->trabajo?->cliente_email }}"
                                                title="Enviar encuesta">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-danger btn-sm eliminar-encuesta" 
                                            data-id="{{ $encuesta->id }}"
                                            title="Eliminar encuesta">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">No hay encuestas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-end mt-3">
                {{ $encuestas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Envío -->
<div class="modal fade" id="enviarEncuestaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Encuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea enviar la encuesta al cliente?</p>
                <p class="mb-0">Email del cliente: <strong id="clienteEmailModal"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarEnvio">Enviar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicialización de DataTables
    const table = $('#encuestasTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[0, 'desc']],
        "pageLength": 25
    });

    // Variables para el envío de encuestas
    let encuestaIdSeleccionada = null;

    // Manejador para mostrar modal de envío
    $('.enviar-encuesta').click(function() {
        encuestaIdSeleccionada = $(this).data('id');
        const emailCliente = $(this).data('email');
        $('#clienteEmailModal').text(emailCliente || 'No disponible');
        $('#enviarEncuestaModal').modal('show');
    });

    // Manejador para confirmar envío
    $('#confirmarEnvio').click(function() {
        if (!encuestaIdSeleccionada) return;

        $.post(`/admin/encuestas/${encuestaIdSeleccionada}/enviar`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            toastr.success('Encuesta enviada correctamente');
            setTimeout(() => window.location.reload(), 1500);
        })
        .fail(function(error) {
            toastr.error('Error al enviar la encuesta');
        })
        .always(function() {
            $('#enviarEncuestaModal').modal('hide');
        });
    });

    // Manejador para eliminación
    $('.eliminar-encuesta').click(function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/encuestas/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Encuesta eliminada correctamente');
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function(error) {
                        toastr.error('Error al eliminar la encuesta');
                    }
                });
            }
        });
    });

    // Manejador para exportar
    $('#exportarEncuestas').click(function(e) {
        e.preventDefault();
        const queryString = $('#filtrosForm').serialize();
        window.location.href = `{{ route('admin.encuestas.exportar') }}?${queryString}`;
    });
});
</script>
@endpush