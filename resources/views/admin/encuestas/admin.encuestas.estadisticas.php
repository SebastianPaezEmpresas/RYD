@extends('admin.layouts.app')

@section('title', 'Estadísticas de Satisfacción')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard de Satisfacción</h1>
        <div>
            <button id="filtroFecha" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                Último Mes
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-periodo="mes">Último Mes</a>
                <a class="dropdown-item" href="#" data-periodo="trimestre">Último Trimestre</a>
                <a class="dropdown-item" href="#" data-periodo="semestre">Último Semestre</a>
                <a class="dropdown-item" href="#" data-periodo="anio">Último Año</a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Encuestas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalEncuestas">
                                {{ $estadisticas['total_encuestas'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tasa de Respuesta
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tasaRespuesta">
                                {{ number_format($estadisticas['tasa_respuesta'], 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Satisfacción Promedio
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="satisfaccionPromedio">
                                {{ number_format($estadisticas['satisfaccion_promedio'], 1) }}/5.0
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tasa de Recomendación
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tasaRecomendacion">
                                {{ number_format($estadisticas['tasa_recomendacion'], 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Evolución de Satisfacción -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Evolución de Satisfacción</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="satisfaccionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución de Puntuaciones -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución de Puntuaciones</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="distribucionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desglose por Aspectos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Desglose por Aspectos</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h4 class="small font-weight-bold">Puntualidad <span class="float-right" id="puntualidadPromedio">{{ number_format($estadisticas['puntualidad_promedio'], 1) }}/5.0</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($estadisticas['puntualidad_promedio']/5)*100 }}%"></div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h4 class="small font-weight-bold">Calidad <span class="float-right" id="calidadPromedio">{{ number_format($estadisticas['calidad_promedio'], 1) }}/5.0</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($estadisticas['calidad_promedio']/5)*100 }}%"></div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h4 class="small font-weight-bold">Atención <span class="float-right" id="atencionPromedio">{{ number_format($estadisticas['atencion_promedio'], 1) }}/5.0</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($estadisticas['atencion_promedio']/5)*100 }}%"></div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h4 class="small font-weight-bold">Limpieza <span class="float-right" id="limpiezaPromedio">{{ number_format($estadisticas['limpieza_promedio'], 1) }}/5.0</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($estadisticas['limpieza_promedio']/5)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('vendor/chart.js/Chart.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Configuración de gráficos
    const satisfaccionCtx = document.getElementById('satisfaccionChart');
    const distribucionCtx = document.getElementById('distribucionChart');

    // Gráfico de evolución
    new Chart(satisfaccionCtx, {
        type: 'line',
        data: {
            labels: @json($estadisticas['fechas']),
            datasets: [{
                label: 'Satisfacción Promedio',
                data: @json($estadisticas['promedios']),
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 5
                    }
                }]
            }
        }
    });

    // Gráfico de distribución
    new Chart(distribucionCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 estrellas', '4 estrellas', '3 estrellas', '2 estrellas', '1 estrella'],
            datasets: [{
                data: @json($estadisticas['distribucion']),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ]
            }]
        },
        options: {
            legend: {
                position: 'bottom'
            }
        }
    });

    // Manejador de cambio de periodo
    $('.dropdown-item').click(function(e) {
        e.preventDefault();
        const periodo = $(this).data('periodo');
        $('#filtroFecha').text($(this).text());
        
        // Actualizar datos vía AJAX
        $.get(`{{ route('admin.encuestas.estadisticas.datos') }}?periodo=${periodo}`)
            .done(function(response) {
                actualizarEstadisticas(response);
            })
            .fail(function(error) {
                alert('Error al cargar los datos');
            });
    });

    function actualizarEstadisticas(datos) {
        // Actualizar tarjetas
        $('#totalEncuestas').text(datos.total_encuestas);
        $('#tasaRespuesta').text(datos.tasa_respuesta.toFixed(1) + '%');
        $('#satisfaccionPromedio').text(datos.satisfaccion_promedio.toFixed(1) + '/5.0');
        $('#tasaRecomendacion').text(datos.tasa_recomendacion.toFixed(1) + '%');

        // Actualizar promedios por aspecto
        $('#puntualidadPromedio').text(datos.puntualidad_promedio.toFixed(1) + '/5.0');
        $('#calidadPromedio').text(datos.calidad_promedio.toFixed(1) + '/5.0');
        $('#atencionPromedio').text(datos.atencion_promedio.toFixed(1) + '/5.0');
        $('#limpiezaPromedio').text(datos.limpieza_promedio.toFixed(1) + '/5.0');

        // Actualizar gráficos
        satisfaccionChart.data.labels = datos.fechas;
        satisfaccionChart.data.datasets[0].data = datos.promedios;
        satisfaccionChart.update();

        distribucionChart.data.datasets[0].data = datos.distribucion;
        distribucionChart.update();
    }
});
</script>
@endpush