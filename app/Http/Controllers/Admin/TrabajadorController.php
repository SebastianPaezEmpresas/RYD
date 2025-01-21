<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = User::where('role', 'trabajador')
            ->orderBy('name')
            ->get();
            
        return view('admin.trabajadores.index', compact('trabajadores'));
    }

    public function create()
    {
        return view('admin.trabajadores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'especialidad' => ['required', 'string', 'max:255'],
        ]);

        $trabajador = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'trabajador',
            'especialidad' => $validated['especialidad'],
        ]);

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador creado exitosamente.');
    }

    public function show(User $trabajador)
    {
        if ($trabajador->role !== 'trabajador') {
            abort(404);
        }

        $trabajosRecientes = $trabajador->trabajos()
            ->with('tipoTrabajo')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.trabajadores.show', compact('trabajador', 'trabajosRecientes'));
    }

    public function edit(User $trabajador)
    {
        if ($trabajador->role !== 'trabajador') {
            abort(404);
        }

        return view('admin.trabajadores.edit', compact('trabajador'));
    }

    public function update(Request $request, User $trabajador)
    {
        if ($trabajador->role !== 'trabajador') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($trabajador->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'especialidad' => ['required', 'string', 'max:255'],
        ]);

        $trabajador->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'especialidad' => $validated['especialidad'],
        ]);

        if (!empty($validated['password'])) {
            $trabajador->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador actualizado exitosamente.');
    }

    public function destroy(User $trabajador)
    {
        if ($trabajador->role !== 'trabajador') {
            abort(404);
        }

        // Verificar si tiene trabajos asignados
        if ($trabajador->trabajos()->exists()) {
            return back()->with('error', 'No se puede eliminar el trabajador porque tiene trabajos asignados.');
        }

        $trabajador->delete();

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador eliminado exitosamente.');
    }

    public function stats(User $trabajador)
    {
        if ($trabajador->role !== 'trabajador') {
            abort(404);
        }

        $stats = [
            'total_trabajos' => $trabajador->trabajos()->count(),
            'trabajos_completados' => $trabajador->trabajos()->where('estado', 'completado')->count(),
            'trabajos_pendientes' => $trabajador->trabajos()->where('estado', 'pendiente')->count(),
            'calificacion_promedio' => $trabajador->trabajos()
                ->whereNotNull('calificacion')
                ->avg('calificacion'),
        ];

        return response()->json($stats);
    }
}