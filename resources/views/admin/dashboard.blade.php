@extends('admin.layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-4xl font-bold text-gray-800 mb-6 text-center">Dashboard de RYD Jardinería</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tarjeta: Total de Trabajadores -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700">Total de Trabajadores</h2>
            <p class="text-5xl font-extrabold text-indigo-600 mt-4">{{ $totalTrabajadores }}</p>
        </div>

        <!-- Tarjeta: Trabajos Completados -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700">Trabajos Completados</h2>
            <p class="text-5xl font-extrabold text-green-500 mt-4">{{ $trabajosCompletados }}</p>
        </div>

        <!-- Tarjeta: Nivel de Satisfacción -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700">Nivel de Satisfacción</h2>
            <p class="text-5xl font-extrabold text-yellow-500 mt-4">{{ number_format($satisfaccionPromedio, 2) }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <!-- Tarjeta: Encuestas Realizadas -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700">Encuestas Completadas</h2>
            <p class="text-5xl font-extrabold text-indigo-500 mt-4">{{ $totalEncuestas }}</p>
        </div>

        <!-- Tarjeta: Próximos Trabajos -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700">Próximos Trabajos</h2>
            <p class="text-5xl font-extrabold text-red-500 mt-4">{{ $trabajosPendientes }}</p>
        </div>

        <!-- Tarjeta: Gráfica de Trabajos -->
        <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Trabajos por Estado</h2>
            <canvas id="trabajosChart"></canvas>
        </div>
    </div>
</div>

<!-- Agregar la librería de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('trabajosChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($trabajosEstados->pluck('estado')) !!},
            datasets: [{
                label: 'Trabajos por Estado',
                data: {!! json_encode($trabajosEstados->pluck('count')) !!},
                backgroundColor: ['#4CAF50', '#FFC107', '#F44336'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Distribución de Trabajos'
                }
            }
        }
    });
</script>
@endsection
