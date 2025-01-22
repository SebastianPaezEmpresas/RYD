@extends('admin.layouts.app')

@section('title', 'Detalle de Encuesta')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Detalle de Encuesta #{{ $encuesta->id }}
        </h1>
        <div>
            <a href="{{ route('admin.encuestas.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Volver
            </a>
            @if($encuesta->estado != 'respondida')
                <button type="button" class="btn btn-primary btn-sm" id="reenviarEncuesta">
                    <i class="fas fa-paper-plane fa-sm"></i> Reenviar Encuesta
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Información del Trabajo -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Trabajo Asociado
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $encuesta->trabajo->descripcion }}
                            </div>
                            <div class="mt-2">
                                <p class="mb-1"><strong>Cliente:</strong> {{ $encuesta->trabajo->cliente->nombre }}</p>
                                <p class="mb-1"><strong>Fecha:</strong> {{ $encuesta->trabajo->fecha_realizacion->format('d/m/Y') }}</p>
                                <p class="mb-0"><strong>Estado:</strong> {{ $encuesta->trabajo->estado }}</p>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de la Encuesta -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Estado de la Encuesta
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
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
                                @endswitch
                            </div>
                            <div class="mt-2">
                                <p class="mb-1"><strong>Creada:</strong> {{ $encuesta->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Último envío:</strong> 
                                    {{ $encuesta->fecha_ultimo_envio ? $encuesta->fecha_ultimo_envio->format('d/m/Y H:i') : 'No enviada' }}
                                </p>
                                <p class="mb-0"><strong>Respondida:</strong>
                                    {{ $encuesta->fecha_respuesta ? $encuesta->fecha_respuesta->format('d/m/Y H:i') : 'Sin respuesta' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Respuestas -->
        @if($encuesta->estado == 'respondida')
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resumen de Respuestas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($encuesta->puntuacion_promedio, 1) }}/5.0
                            </div>
                            <div class="mt-2">
                                <p class="mb-1">
                                    <strong>Satisfacción general:</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $encuesta->puntuacion_promedio ? 'text-warning' : 'text-gray-300' }}"></i>
                                    @endfor
                                </p>
                                <p class="mb-0">
                                    <strong>Recomendaría:</strong>
                                    @if($encuesta->recomienda)
                                        <span class="text-success"><i class="fas fa-check"></i> Sí</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times"></i> No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Detalle de Respuestas -->
    @if($encuesta->estado == 'respondida')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Respuestas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                            <th>Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Puntualidad del servicio</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $encuesta->puntualidad ? 'text-warning' : 'text-gray-300' }}"></i>
                                @endfor
                            </td>
                        </tr>
                        <tr>
                            <td>Calidad del trabajo</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $encuesta->calidad ? 'text-warning' : 'text-gray-300' }}"></i>
                                @endfor
                            </td>
                        </tr>
                        <tr>
                            <td>Atención del personal</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $encuesta->atencion ? 'text-warning' : 'text-gray-300' }}"></i>
                                @endfor
                            </td>
                        </tr>
                        <tr>
                            <td>Limpieza post-trabajo</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $encuesta->limpieza ? 'text-warning' : 'text-gray-300' }}"></i>
                                @endfor
                            </td>
                        </tr>
                        <tr>
                            <td>Comentarios adicionales</td>
                            <td>{{ $encuesta->comentarios ?: 'Sin comentarios' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Historial de Envíos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Envíos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="historialTable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Email</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encuesta->historial_envios as $envio)
                            <tr>
                                <td>{{ $envio->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $envio->tipo }}</td>
                                <td>{{ $envio->email }}</td>
                                <td>
                                    @if($envio->estado == 'exitoso')
                                        <span class="badge badge-success">Exitoso</span>
                                    @else
                                        <span class="badge badge-danger">Fallido</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay registros de envíos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // DataTable para historial
    $('#historialTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[0, 'desc']]
    });

    // Reenviar encuesta
    $('#reenviarEncuesta').click(function() {
        if(confirm('¿Está seguro que desea reenviar esta encuesta?')) {
            $.post('{{ route('admin.encuestas.reenviar', $encuesta->id) }}')
                .done(function(response) {
                    alert('Encuesta reenviada correctamente');
                    location.reload();
                })
                .fail(function(error) {
                    alert('Error al reenviar la encuesta');
                });
        }
    });
});
</script>
@endpush