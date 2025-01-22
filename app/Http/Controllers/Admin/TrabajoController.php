<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Trabajador;
use App\Models\TipoTrabajo;
use App\Models\Encuesta;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TrabajoController extends Controller
{
   public function index()
   {
       $trabajadores = Trabajador::all();
       $tipoTrabajos = TipoTrabajo::all();
       
       $eventos = Trabajo::with(['trabajador', 'tipoTrabajo', 'cliente'])
           ->get()
           ->map(function ($trabajo) {
               $event = $trabajo->toCalendarEvent();
               $event['id'] = $trabajo->id;
               $event['editable'] = true;
               return $event;
           });

       return view('admin.trabajos.index', compact('trabajadores', 'tipoTrabajos', 'eventos'));
   }

   public function store(Request $request)
   {
       $validatedData = $request->validate([
           'titulo' => 'required|string|max:255',
           'descripcion' => 'nullable|string',
           'trabajador_id' => 'required|exists:trabajadores,id',
           'tipo_trabajo_id' => 'nullable|exists:tipo_trabajos,id',
           'estado' => 'required|in:pendiente,en_progreso,completado',
           'fecha_inicio' => 'required|date',
           'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
           'fecha_realizacion' => 'nullable|date',
           'cliente_email' => 'nullable|email',
           'notas_finales' => 'nullable|string'
       ]);

       try {
           $validatedData['link_compartido'] = Str::random(32);
           $trabajo = Trabajo::create($validatedData);
           
           if (!empty($validatedData['cliente_email'])) {
               Encuesta::create([
                   'trabajo_id' => $trabajo->id,
                   'token' => Str::random(40),
                   'estado' => 'pendiente'
               ]);
           }

           if ($request->hasFile('evidencias_inicio')) {
               $evidencias = [];
               foreach ($request->file('evidencias_inicio') as $file) {
                   $path = $file->store('evidencias/inicio', 'public');
                   $evidencias[] = $path;
               }
               $trabajo->evidencias_inicio = $evidencias;
               $trabajo->save();
           }

           return response()->json([
               'success' => true,
               'message' => 'Trabajo creado con éxito',
               'evento' => $trabajo->toCalendarEvent()
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Error al crear el trabajo: ' . $e->getMessage()
           ], 500);
       }
   }

   public function show(Trabajo $trabajo)
   {
       try {
           $trabajo->load(['trabajador', 'tipoTrabajo', 'cliente', 'encuesta']);
           
           if (request()->wantsJson()) {
               return response()->json([
                   'success' => true,
                   'trabajo' => $trabajo
               ]);
           }
           
           return view('admin.trabajos.show', compact('trabajo'));
       } catch (\Exception $e) {
           if (request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Error al cargar el trabajo: ' . $e->getMessage()
               ], 500);
           }
           
           return back()->with('error', 'Error al cargar el trabajo');
       }
   }

   public function update(Request $request, Trabajo $trabajo)
   {
       $validatedData = $request->validate([
           'titulo' => 'required|string|max:255',
           'descripcion' => 'nullable|string',
           'trabajador_id' => 'required|exists:trabajadores,id',
           'tipo_trabajo_id' => 'nullable|exists:tipo_trabajos,id',
           'estado' => 'required|in:pendiente,en_progreso,completado',
           'fecha_inicio' => 'required|date',
           'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
           'fecha_realizacion' => 'nullable|date',
           'cliente_email' => 'nullable|email',
           'notas_finales' => 'nullable|string'
       ]);

       try {
           if (!empty($validatedData['cliente_email']) && !$trabajo->encuesta) {
               Encuesta::create([
                   'trabajo_id' => $trabajo->id,
                   'token' => Str::random(40),
                   'estado' => 'pendiente'
               ]);
           }

           $trabajo->update($validatedData);

           if ($request->hasFile('evidencias_fin')) {
               $evidencias = $trabajo->evidencias_fin ?? [];
               foreach ($request->file('evidencias_fin') as $file) {
                   $path = $file->store('evidencias/fin', 'public');
                   $evidencias[] = $path;
               }
               $trabajo->evidencias_fin = $evidencias;
               $trabajo->save();
           }

           $trabajo = $trabajo->fresh();
           $evento = $trabajo->toCalendarEvent();
           $evento['id'] = $trabajo->id;
           
           return response()->json([
               'success' => true,
               'message' => 'Trabajo actualizado con éxito',
               'evento' => $evento
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Error al actualizar el trabajo: ' . $e->getMessage()
           ], 500);
       }
   }

   public function destroy(Trabajo $trabajo)
   {
       try {
           if (!empty($trabajo->evidencias_inicio)) {
               foreach ($trabajo->evidencias_inicio as $evidencia) {
                   Storage::disk('public')->delete($evidencia);
               }
           }
           if (!empty($trabajo->evidencias_fin)) {
               foreach ($trabajo->evidencias_fin as $evidencia) {
                   Storage::disk('public')->delete($evidencia);
               }
           }

           if ($trabajo->encuesta) {
               $trabajo->encuesta->delete();
           }

           $trabajo->delete();
           
           if (request()->wantsJson()) {
               return response()->json([
                   'success' => true,
                   'message' => 'Trabajo eliminado con éxito'
               ]);
           }

           return redirect()->route('admin.trabajos.index')
                          ->with('success', 'Trabajo eliminado con éxito');
       } catch (\Exception $e) {
           if (request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Error al eliminar el trabajo: ' . $e->getMessage()
               ], 500);
           }

           return back()->with('error', 'Error al eliminar el trabajo');
       }
   }

   public function filtrar(Request $request)
   {
       try {
           $query = Trabajo::with(['trabajador', 'tipoTrabajo', 'cliente', 'encuesta']);

           if ($request->has('id')) {
               $query->where('id', $request->id);
           }

           if ($request->estado) {
               $query->where('estado', $request->estado);
           }

           if ($request->trabajador_id) {
               $query->where('trabajador_id', $request->trabajador_id);
           }

           if ($request->fecha_inicio) {
               $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
           }

           if ($request->fecha_fin) {
               $query->whereDate('fecha_fin', '<=', $request->fecha_fin);
           }

           $trabajos = $query->get();

           if ($request->wantsJson()) {
               return response()->json([
                   'success' => true,
                   'data' => $trabajos->map(function($trabajo) {
                       return [
                           'id' => $trabajo->id,
                           'titulo' => $trabajo->titulo,
                           'descripcion' => $trabajo->descripcion,
                           'estado' => $trabajo->estado,
                           'fecha_inicio' => $trabajo->fecha_inicio,
                           'fecha_fin' => $trabajo->fecha_fin,
                           'trabajador' => $trabajo->trabajador,
                           'tipo_trabajo' => $trabajo->tipoTrabajo,
                           'cliente_email' => $trabajo->cliente_email
                       ];
                   })
               ]);
           }

           return $trabajos->map(function($trabajo) {
               $event = $trabajo->toCalendarEvent();
               $event['id'] = $trabajo->id;
               $event['editable'] = true;
               return $event;
           });
       } catch (\Exception $e) {
           if ($request->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Error al filtrar trabajos: ' . $e->getMessage()
               ], 500);
           }
           throw $e;
       }
   }

   public function actualizarFechas(Request $request, Trabajo $trabajo)
   {
       $validatedData = $request->validate([
           'fecha_inicio' => 'required|date',
           'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
       ]);

       try {
           $trabajo->update($validatedData);
           
           return response()->json([
               'success' => true,
               'message' => 'Fechas actualizadas con éxito',
               'evento' => $trabajo->toCalendarEvent()
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Error al actualizar las fechas: ' . $e->getMessage()
           ], 500);
       }
   }

   public function getDetalle(Trabajo $trabajo)
   {
       try {
           $trabajo->load(['trabajador', 'tipoTrabajo', 'cliente', 'encuesta']);
           return response()->json([
               'success' => true,
               'trabajo' => $trabajo
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Error al cargar los detalles del trabajo: ' . $e->getMessage()
           ], 500);
       }
   }
}