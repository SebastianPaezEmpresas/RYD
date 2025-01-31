<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $stats = [
        'totalTrabajadores' => User::where('role', 'worker')
                                 ->where('active', true)
                                 ->count(),
        'trabajosCompletados' => 156,  // Dato simulado
        'satisfaccionPromedio' => 98.5, // Dato simulado
    ];

    $trabajosEstados = collect([
        ['estado' => 'Completados', 'count' => 65],
        ['estado' => 'En Progreso', 'count' => 25],
        ['estado' => 'Pendientes', 'count' => 10]
    ]);

    return view('admin.dashboard', compact('stats', 'trabajosEstados'));
}
}