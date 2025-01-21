<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trabajador;

class TrabajadoresController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::all(); // Obtiene todos los trabajadores
        return view('admin.trabajadores', compact('trabajadores'));
    }

    public function create()
    {
        return view('admin.trabajadores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:trabajadores,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_contratacion' => 'required|date',
            'salario' => 'nullable|numeric',
            'cargo' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        Trabajador::create($request->all());

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador creado con éxito.');
    }

    public function edit($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        return view('admin.trabajadores.edit', compact('trabajador'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:trabajadores,email,'.$id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_contratacion' => 'required|date',
            'salario' => 'nullable|numeric',
            'cargo' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        $trabajador = Trabajador::findOrFail($id);
        $trabajador->update($request->all());

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador actualizado con éxito.');
    }

    public function destroy($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->delete();

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador eliminado con éxito.');
    }
}