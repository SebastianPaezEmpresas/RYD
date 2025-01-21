<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trabajo;
use App\Models\TipoTrabajo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrabajoAsignado;
use App\Mail\TrabajoCompletado;

class TrabajoController extends Controller
{
    public function index()
    {
        // Obtener trabajadores
        $trabajadores = User::where('role', 'trabajador')
                          ->orderBy('name')
                          ->get(['id', 'name', 'especialidad']);
        
        // Obtener tipos de trabajo
        $tiposTrabajos = TipoTrabajo::orderBy('nombre')->get();
        
        // Obtener todos los trabajos con sus relaciones
        $trabajos = Trabajo::with(['trabajador', 'tipoTrabajo'])
                          ->orderBy('fecha_inicio', 'desc')
                          ->get();
        
        // Obtener próximos trabajos (siguientes 5 días)
        $proximosTrabajos = Trabajo::with('trabajador')
            ->where('fecha_inicio', '>', now())
            ->where('fecha_inicio', '<=', now()->addDays(5))
            ->orderBy('fecha_inicio')
            ->get();
            
        // Preparar datos para el calendario
        $trabajosCalendario = $trabajos->map(function ($trabajo) {
            return [
                'id' => $trabajo->id,
                'title' => $trabajo->titulo,
                'start' => $trabajo->fecha_inicio->format('Y-m-d H:i:s'),
                'end' => $trabajo->fecha_fin->format('Y-m-d H:i:s'),
                'backgroundColor' => $this->getStatusColor($trabajo->estado),
                'borderColor' => $this->getStatusColor($trabajo->estado),
                'extendedProps' => [
                    'descripcion' => Str::limit($trabajo->descripcion, 100),
                    'trabajador' => $trabajo->trabajador->name,
                    'estado' => $trabajo->estado,
                    'tipo' => $trabajo->tipoTrabajo->nombre
                ]
            ];
        });

        return view('admin.trabajos.index', compact(
            'trabajos',
            'trabajadores',
            'tiposTrabajos',
            'proximosTrabajos',
            'trabajosCalendario'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'estado' => 'required|in:pendiente,en_progreso,completado',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'trabajador_id' => 'required|exists:users,id',
            'tipo_trabajo_id' => 'required|exists:tipo_trabajos,id',
            'cliente_email' => 'nullable|email',
            'evidencias' => 'nullable|array|max:5',
            'evidencias.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:3072'
        ]);

        $evidencias = [];
        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $file) {
                $path = $file->store('evidencias', 'public');
                $evidencias[] = [
                    'path' => $path,
                    'nombre' => $file->getClientOriginalName(),
                    'tipo' => $file->getClientMimeType()
                ];
            }
        }

        $trabajo = Trabajo::create([
            ...$validatedData,
            'evidencias_inicio' => $evidencias,
            'link_compartido' => Str::random(32)
        ]);

        // Enviar notificación al trabajador
        if ($trabajo->trabajador->email) {
            try {
                Mail::to($trabajo->trabajador->email)
                    ->send(new TrabajoAsignado($trabajo));
            } catch (\Exception $e) {
                // Log el error pero continuar
                \Log::error("Error enviando email: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.trabajos.index')
            ->with('success', 'Trabajo creado exitosamente');
    }

    public function show($id)
    {
        $trabajo = Trabajo::with(['trabajador', 'tipoTrabajo'])->findOrFail($id);
        return view('admin.trabajos.show', compact('trabajo'));
    }

    public function edit($id)
    {
        $trabajo = Trabajo::findOrFail($id);
        $trabajadores = User::where('role', 'trabajador')->get();
        $tiposTrabajos = TipoTrabajo::all();
        
        return view('admin.trabajos.edit', compact('trabajo', 'trabajadores', 'tiposTrabajos'));
    }

    public function update(Request $request, $id)
    {
        $trabajo = Trabajo::findOrFail($id);
        
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'estado' => 'required|in:pendiente,en_progreso,completado',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'trabajador_id' => 'required|exists:users,id',
            'tipo_trabajo_id' => 'required|exists:tipo_trabajos,id',
            'cliente_email' => 'nullable|email',
            'evidencias' => 'nullable|array|max:5',
            'evidencias.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:3072'
        ]);

        if ($request->hasFile('evidencias')) {
            $evidencias = $trabajo->evidencias_inicio ?? [];
            foreach ($request->file('evidencias') as $file) {
                $path = $file->store('evidencias', 'public');
                $evidencias[] = [
                    'path' => $path,
                    'nombre' => $file->getClientOriginalName(),
                    'tipo' => $file->getClientMimeType()
                ];
            }
            $validatedData['evidencias_inicio'] = $evidencias;
        }

        $trabajo->update($validatedData);

        return redirect()->route('admin.trabajos.show', $trabajo->id)
            ->with('success', 'Trabajo actualizado exitosamente');
    }

    public function destroy($id)
    {
        $trabajo = Trabajo::findOrFail($id);
        
        // Eliminar evidencias
        if (!empty($trabajo->evidencias_inicio)) {
            foreach ($trabajo->evidencias_inicio as $evidencia) {
                Storage::disk('public')->delete($evidencia['path']);
            }
        }
        if (!empty($trabajo->evidencias_fin)) {
            foreach ($trabajo->evidencias_fin as $evidencia) {
                Storage::disk('public')->delete($evidencia['path']);
            }
        }

        $trabajo->delete();

        return redirect()->route('admin.trabajos.index')
            ->with('success', 'Trabajo eliminado exitosamente');
    }

    public function completar(Request $request, $id)
    {
        $trabajo = Trabajo::findOrFail($id);
        
        $validatedData = $request->validate([
            'evidencias_fin' => 'required|array|max:5',
            'evidencias_fin.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:3072',
            'notas_finales' => 'required|string'
        ]);

        $evidenciasFin = [];
        foreach ($request->file('evidencias_fin') as $file) {
            $path = $file->store('evidencias', 'public');
            $evidenciasFin[] = [
                'path' => $path,
                'nombre' => $file->getClientOriginalName(),
                'tipo' => $file->getClientMimeType()
            ];
        }

        $trabajo->update([
            'estado' => 'completado',
            'evidencias_fin' => $evidenciasFin,
            'notas_finales' => $request->notas_finales
        ]);

        // Enviar notificación al cliente
        if ($trabajo->cliente_email) {
            try {
                Mail::to($trabajo->cliente_email)
                    ->send(new TrabajoCompletado($trabajo));
            } catch (\Exception $e) {
                \Log::error("Error enviando email: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.trabajos.show', $trabajo)
            ->with('success', 'Trabajo marcado como completado');
    }

    private function getStatusColor($estado)
    {
        return [
            'pendiente' => '#FCD34D',    // yellow-400
            'en_progreso' => '#60A5FA',  // blue-400
            'completado' => '#34D399',   // green-400
        ][$estado] ?? '#9CA3AF';         // gray-400 default
    }

    public function uploadEvidencia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:3072'
        ]);

        $file = $request->file('file');
        $path = $file->store('temp-evidencias', 'public');
        
        return response()->json([
            'path' => $path,
            'nombre' => $file->getClientOriginalName(),
            'tipo' => $file->getClientMimeType()
        ]);
    }
}