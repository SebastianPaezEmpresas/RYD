<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\User;
use App\Models\TrabajoFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrabajoController extends Controller
{
   public function index()
   {
       $trabajos = Trabajo::with(['worker'])
           ->latest()
           ->paginate(10);
   
       $trabajadores = User::where('role', 'worker')->where('active', true)->get();
   
       return view('admin.trabajos.index', compact('trabajos', 'trabajadores'));
   }
  
   public function create()
   {
       $trabajadores = User::where('role', 'worker')->where('active', true)->get();
       return view('admin.trabajos.create', compact('trabajadores'));
   }

   public function store(Request $request)
   {
       $validated = $request->validate([
           'titulo' => 'required|string|max:255',
           'tipo_trabajo' => 'required|string|max:255',
           'cliente' => 'required|string|max:255',
           'direccion' => 'required|string|max:255',
           'descripcion' => 'required|string',
           'presupuesto' => 'required|numeric|min:0',
           'worker_id' => 'required|exists:users,id',
           'fecha_programada' => 'required|date',
           'cliente_email' => 'required|email'
       ]);

       $validated['estado'] = 'pendiente';
       $validated['encuesta_token'] = Str::random(32);
       
       $trabajo = Trabajo::create($validated);

       return redirect()->route('admin.trabajos.index')
           ->with('success', 'Trabajo creado exitosamente');
   }

   public function show(Trabajo $trabajo)
   {
       $trabajo->load(['worker', 'fotos', 'encuesta']);
       $encuestaUrl = route('encuestas.create', $trabajo->encuesta_token);
       return view('admin.trabajos.show', compact('trabajo', 'encuestaUrl'));
   }

   public function edit(Trabajo $trabajo)
   {
       $workers = User::where('role', 'worker')->where('active', true)->get();
       return view('admin.trabajos.edit', compact('trabajo', 'workers'));
   }

   public function update(Request $request, Trabajo $trabajo)
   {
       $validated = $request->validate([
           'titulo' => 'required|string|max:255',
           'tipo_trabajo' => 'required|string|max:255', 
           'cliente' => 'required|string|max:255',
           'direccion' => 'required|string|max:255',
           'descripcion' => 'required|string',
           'presupuesto' => 'required|numeric|min:0',
           'estado' => 'required|in:pendiente,en_progreso,completado',
           'worker_id' => 'required|exists:users,id',
           'fecha_programada' => 'required|date',
           'fecha_inicio' => 'nullable|date',
           'fecha_fin' => 'nullable|date',
           'notas' => 'nullable|string',
           'cliente_email' => 'required|email'
       ]);

       $trabajo->update($validated);

       return redirect()->route('admin.trabajos.index')
           ->with('success', 'Trabajo actualizado exitosamente');
   }

   public function destroy(Trabajo $trabajo)
{
    try {
        DB::beginTransaction();
        
        $trabajo->fotos()->each(function($foto) {
            Storage::disk('public')->delete($foto->ruta);
            $foto->delete();
        });
        
        $trabajo->encuesta()->delete();
        $trabajo->delete();
        
        DB::commit();
        
        return redirect()->route('admin.trabajos.index')
            ->with('success', 'Trabajo eliminado exitosamente');
            
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->route('admin.trabajos.index')
            ->with('error', 'Error al eliminar el trabajo');
    }
}

   public function workerTrabajos()
   {
       $trabajos = auth()->user()->trabajos()
           ->orderBy('fecha_programada', 'desc')
           ->paginate(10);
           
       return view('dashboard.trabajos', compact('trabajos'));
   }

   public function iniciarTrabajo(Request $request, Trabajo $trabajo)
   {
       $request->validate([
           'foto' => 'required|image|max:2048'
       ]);

       $path = $request->file('foto')->store('public/trabajos');
       $path = str_replace('public/', '', $path);

       DB::transaction(function () use ($trabajo, $path) {
           $trabajo->update([
               'estado' => 'en_progreso',
               'fecha_inicio' => now()
           ]);
   
           $trabajo->fotos()->create([
               'ruta' => $path,
               'etapa' => 'inicio',
               'fecha_captura' => now()
           ]);
       });
   
       return redirect()->route('worker.trabajos')
           ->with('success', 'Trabajo iniciado correctamente');
   }

   public function finalizarTrabajo(Request $request, Trabajo $trabajo)
   {
       $request->validate([
           'foto' => 'required|image|max:2048'
       ]);

       $path = $request->file('foto')->store('public/trabajos');
       $path = str_replace('public/', '', $path);

       DB::transaction(function () use ($trabajo, $path) {
           $trabajo->update([
               'estado' => 'completado',
               'fecha_fin' => now()
           ]);
   
           $trabajo->fotos()->create([
               'ruta' => $path,
               'etapa' => 'fin',
               'fecha_captura' => now()
           ]);
       });

       $encuestaUrl = route('encuestas.create', $trabajo->encuesta_token);
       
       return redirect()->route('worker.trabajos')
           ->with('success', "Trabajo finalizado. Enlace para encuesta: {$encuestaUrl}");
   }

   public function deleteFoto($id)
   {
       $foto = TrabajoFoto::findOrFail($id);
       Storage::delete('public/' . $foto->ruta);
       $foto->delete();
       
       return response()->json(['success' => true]);
   }

   public function getEncuestaUrl(Trabajo $trabajo)
   {
       return response()->json([
           'url' => route('encuestas.create', $trabajo->encuesta_token)
       ]);
   }
   protected function notifyAdmin($message)
{
    $admin = User::where('role', 'admin')->first();
    
    Notification::create([
        'user_id' => $admin->id,
        'title' => 'Nuevo Estado de Trabajo',
        'message' => $message,
        'type' => 'info'
    ]);
}
public function schedule()
    {
        $workers = User::where('role', 'worker')
                    ->where('active', true)
                    ->get();
        return view('admin.trabajos.schedule', compact('workers'));
    }

    public function scheduleStore(Request $request)
    {
        $validated = $request->validate([
            'conjunto_nombre' => 'required|string',
            'conjunto_direccion' => 'required|string',
            'conjunto_email' => 'required|email',
            'presupuesto_casa' => 'required|numeric|min:0',
            'worker_id' => 'required|exists:users,id',
            'descripcion' => 'required|string',
            'tipo_trabajo' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'casa_inicial' => 'required|integer|min:1',
            'casa_final' => 'required|integer|gte:casa_inicial',
            'casas_por_dia' => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function() use ($request, $validated) {
                $fechaInicio = new \DateTime($validated['fecha_inicio']);
                $fechaFin = new \DateTime($validated['fecha_fin']);
                $casaActual = $validated['casa_inicial'];
                
                while ($fechaInicio <= $fechaFin && $casaActual <= $validated['casa_final']) {
                    $casasFinal = min(
                        $casaActual + $validated['casas_por_dia'] - 1,
                        $validated['casa_final']
                    );
                    
                    Trabajo::create([
                        'worker_id' => $validated['worker_id'],
                        'fecha_programada' => $fechaInicio->format('Y-m-d'),
                        'tipo_trabajo' => $validated['tipo_trabajo'],
                        'descripcion' => $validated['descripcion'],
                        'estado' => 'pendiente',
                        'titulo' => "{$validated['conjunto_nombre']} - Casas $casaActual a $casasFinal",
                        'cliente' => "Casas $casaActual a $casasFinal",
                        'direccion' => "{$validated['conjunto_direccion']}",
                        'presupuesto' => $validated['presupuesto_casa'] * ($casasFinal - $casaActual + 1),
                        'cliente_email' => $validated['conjunto_email'],
                        'encuesta_token' => Str::random(32)
                    ]);

                    $casaActual = $casasFinal + 1;
                    $fechaInicio->modify('+1 day');
                }
            });

            return redirect()->route('admin.trabajos.index')
                ->with('success', 'Trabajos programados exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al programar los trabajos: ' . $e->getMessage())
                ->withInput();
        }
    }
}