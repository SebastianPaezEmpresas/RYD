<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trabajador;
use App\Models\Trabajo;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTrabajadores = Trabajador::count();
        $trabajosCompletados = Trabajo::where('estado', 'completado')->count();
        $trabajosPendientes = Trabajo::where('estado', 'pendiente')->count();
        $satisfaccionPromedio = Encuesta::avg('calificacion');
        $totalEncuestas = Encuesta::count();
    
        $trabajosEstados = Trabajo::selectRaw("estado, COUNT(*) as count")
                                 ->groupBy('estado')
                                 ->get();
    
        return view('admin.dashboard', compact(
            'totalTrabajadores', 
            'trabajosCompletados', 
            'trabajosPendientes',
            'satisfaccionPromedio',
            'totalEncuestas',
            'trabajosEstados'
        ));
    }
}
