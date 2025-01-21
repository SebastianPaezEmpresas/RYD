<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trabajador;
use App\Models\Trabajo;
use App\Models\Encuesta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de trabajadores y crecimiento
        $totalTrabajadores = Trabajador::count();
        $trabajadoresMesAnterior = Trabajador::whereMonth(
            'created_at', '=', Carbon::now()->subMonth()->month
        )->count();
        
        $crecimientoTrabajadores = $trabajadoresMesAnterior > 0 
            ? (($totalTrabajadores - $trabajadoresMesAnterior) / $trabajadoresMesAnterior) * 100 
            : 0;

        // Trabajos completados y eficiencia
        $trabajosCompletados = Trabajo::where('estado', 'completado')->count();
        $totalTrabajos = Trabajo::count();
        $eficienciaTrabajos = $totalTrabajos > 0 
            ? ($trabajosCompletados / $totalTrabajos) * 100 
            : 0;

        // Nivel de satisfacción
        $satisfaccionPromedio = Encuesta::avg('puntuacion') ?? 0;
        $valoracionPromedio = number_format($satisfaccionPromedio, 1);

        // Total de encuestas y tasa de respuesta
        $totalEncuestas = Encuesta::count();
        $tasaRespuesta = Encuesta::where('completada', true)->count();
        $porcentajeRespuesta = $totalEncuestas > 0 
            ? ($tasaRespuesta / $totalEncuestas) * 100 
            : 0;

        // Próximos trabajos y progreso
        $trabajosPendientes = Trabajo::where('estado', 'pendiente')->count();
        $progresoTrabajos = $totalTrabajos > 0 
            ? ($trabajosCompletados / $totalTrabajos) * 100 
            : 0;

        // Trabajos por estado para el gráfico
        $trabajosEstados = Trabajo::select('estado', DB::raw('count(*) as count'))
            ->groupBy('estado')
            ->get();

        // Actividad reciente
        $actividadReciente = DB::table('trabajos')
            ->select(
                'trabajos.id',
                'trabajos.titulo',
                'trabajos.estado',
                'trabajos.updated_at',
                'trabajadores.nombre as trabajador_nombre'
            )
            ->leftJoin('trabajadores', 'trabajos.trabajador_id', '=', 'trabajadores.id')
            ->orderBy('trabajos.updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTrabajadores',
            'crecimientoTrabajadores',
            'trabajosCompletados',
            'eficienciaTrabajos',
            'satisfaccionPromedio',
            'valoracionPromedio',
            'totalEncuestas',
            'porcentajeRespuesta',
            'trabajosPendientes',
            'progresoTrabajos',
            'trabajosEstados',
            'actividadReciente'
        ));
    }
}