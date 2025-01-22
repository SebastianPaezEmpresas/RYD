<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EncuestaController extends Controller
{
    public function index()
    {
        $encuestas = Encuesta::with(['trabajo.cliente'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.encuestas.index', compact('encuestas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
        ]);

        try {
            $trabajo = Trabajo::findOrFail($validatedData['trabajo_id']);
            
            if (!$trabajo->cliente_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'El trabajo no tiene un email de cliente registrado'
                ], 422);
            }

            $encuesta = Encuesta::create([
                'trabajo_id' => $trabajo->id,
                'token' => Str::random(40),
                'estado' => 'pendiente'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encuesta creada con éxito',
                'encuesta' => $encuesta
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la encuesta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Encuesta $encuesta)
    {
        $encuesta->load(['trabajo']);
        $encuesta->load(['historial_envios' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        
        return view('admin.encuestas.show', compact('encuesta'));
    }

    public function enviar(Encuesta $encuesta)
    {
        try {
            if (!$encuesta->trabajo->cliente_email) {
                throw new \Exception('No hay email de cliente registrado');
            }

            // Aquí irá la lógica de envío de email
            // Mail::to($encuesta->trabajo->cliente_email)->send(new EncuestaSatisfaccionMail($encuesta));

            $encuesta->update([
                'estado' => 'enviada',
                'fecha_envio' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encuesta enviada con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar la encuesta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reenviar(Encuesta $encuesta)
    {
        try {
            if (!$encuesta->trabajo->cliente_email) {
                throw new \Exception('No hay email de cliente registrado');
            }

            if ($encuesta->estado === 'respondida') {
                throw new \Exception('Esta encuesta ya ha sido respondida');
            }

            // Aquí irá la lógica de envío de email
            // Mail::to($encuesta->trabajo->cliente_email)->send(new EncuestaSatisfaccionMail($encuesta));

            $encuesta->update([
                'fecha_envio' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encuesta reenviada con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reenviar la encuesta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function estadisticas()
    {
        $fechaInicio = request('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = request('fecha_fin', Carbon::now());

        $stats = [
            'total_encuestas' => Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])->count(),
            'tasa_respuesta' => $this->calcularTasaRespuesta($fechaInicio, $fechaFin),
            'satisfaccion_promedio' => $this->calcularSatisfaccionPromedio($fechaInicio, $fechaFin),
            'tasa_recomendacion' => $this->calcularTasaRecomendacion($fechaInicio, $fechaFin),
            'puntualidad_promedio' => Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->whereNotNull('puntualidad')
                ->avg('puntualidad') ?? 0,
            'calidad_promedio' => Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->whereNotNull('calidad')
                ->avg('calidad') ?? 0,
            'atencion_promedio' => Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->whereNotNull('atencion')
                ->avg('atencion') ?? 0,
            'limpieza_promedio' => Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->whereNotNull('limpieza')
                ->avg('limpieza') ?? 0,
            'distribucion' => $this->obtenerDistribucionPuntuaciones($fechaInicio, $fechaFin),
            'fechas' => $this->obtenerFechasEvolucion($fechaInicio, $fechaFin),
            'promedios' => $this->obtenerPromediosEvolucion($fechaInicio, $fechaFin),
        ];

        return view('admin.encuestas.estadisticas', compact('stats'));
    }

    public function exportar(Request $request)
    {
        $query = Encuesta::with(['trabajo.cliente'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtros si existen
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->has('cliente')) {
            $query->whereHas('trabajo.cliente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        $encuestas = $query->get();

        // Crear el contenido CSV
        $csvContent = "ID,Trabajo,Cliente,Fecha Trabajo,Estado,Última Actualización\n";
        
        foreach ($encuestas as $encuesta) {
            $csvContent .= implode(',', [
                $encuesta->id,
                str_replace(',', ';', $encuesta->trabajo->descripcion),
                str_replace(',', ';', $encuesta->trabajo->cliente->nombre),
                $encuesta->trabajo->fecha_realizacion->format('d/m/Y'),
                $encuesta->estado,
                $encuesta->updated_at->format('d/m/Y H:i')
            ]) . "\n";
        }

        // Generar la respuesta con el archivo CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="encuestas.csv"',
        ];

        return response($csvContent, 200, $headers);
    }

    public function destroy(Encuesta $encuesta)
    {
        try {
            $encuesta->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Encuesta eliminada con éxito'
                ]);
            }

            return redirect()->route('admin.encuestas.index')
                           ->with('success', 'Encuesta eliminada con éxito');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la encuesta: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al eliminar la encuesta: ' . $e->getMessage());
        }
    }

    private function calcularTasaRespuesta($fechaInicio, $fechaFin)
    {
        $total = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])->count();
        if ($total === 0) return 0;

        $respondidas = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'respondida')
            ->count();

        return ($respondidas / $total) * 100;
    }

    private function calcularSatisfaccionPromedio($fechaInicio, $fechaFin)
    {
        $promedios = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'respondida')
            ->selectRaw('(puntualidad + calidad + atencion + limpieza) / 4 as promedio')
            ->pluck('promedio');

        return $promedios->avg() ?? 0;
    }

    private function calcularTasaRecomendacion($fechaInicio, $fechaFin)
    {
        $total = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'respondida')
            ->count();

        if ($total === 0) return 0;

        $recomiendan = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'respondida')
            ->where('recomienda', true)
            ->count();

        return ($recomiendan / $total) * 100;
    }

    private function obtenerDistribucionPuntuaciones($fechaInicio, $fechaFin)
    {
        $distribucion = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Encuesta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('estado', 'respondida')
                ->whereRaw('(puntualidad + calidad + atencion + limpieza) / 4 = ?', [$i])
                ->count();
            $distribucion[] = $count;
        }
        return $distribucion;
    }

    private function obtenerFechasEvolucion($fechaInicio, $fechaFin)
    {
        $fechas = [];
        $fecha = Carbon::parse($fechaInicio);
        while ($fecha <= Carbon::parse($fechaFin)) {
            $fechas[] = $fecha->format('Y-m-d');
            $fecha->addDay();
        }
        return $fechas;
    }

    private function obtenerPromediosEvolucion($fechaInicio, $fechaFin)
    {
        $promedios = [];
        $fecha = Carbon::parse($fechaInicio);
        while ($fecha <= Carbon::parse($fechaFin)) {
            $promedio = Encuesta::whereDate('created_at', $fecha)
                ->where('estado', 'respondida')
                ->selectRaw('(puntualidad + calidad + atencion + limpieza) / 4 as promedio')
                ->pluck('promedio')
                ->avg() ?? 0;
            $promedios[] = round($promedio, 2);
            $fecha->addDay();
        }
        return $promedios;
    }
}