<?php

namespace App\Http\Controllers;

use App\Models\TipoTrabajo;
use Illuminate\Http\Request;

class TipoTrabajoController extends Controller
{
   public function index()
   {
       $tipos = TipoTrabajo::all();
       return view('admin.tipos_trabajo.index', compact('tipos'));
   }

   public function create()
   {
       return view('admin.tipos_trabajo.create');
   }

   public function store(Request $request)
   {
       $validated = $request->validate([
           'nombre' => 'required|string|max:255',
           'descripcion' => 'nullable|string'
       ]);

       TipoTrabajo::create($validated);

       return redirect()->route('admin.tipos_trabajo.index')
           ->with('success', 'Tipo de trabajo creado exitosamente');
   }

   public function edit(TipoTrabajo $tipoTrabajo)
   {
       return view('admin.tipos_trabajo.edit', compact('tipoTrabajo'));
   }

   public function update(Request $request, TipoTrabajo $tipoTrabajo)
   {
       $validated = $request->validate([
           'nombre' => 'required|string|max:255',
           'descripcion' => 'nullable|string'
       ]);

       $tipoTrabajo->update($validated);

       return redirect()->route('admin.tipos_trabajo.index')
           ->with('success', 'Tipo de trabajo actualizado exitosamente');
   }

   public function destroy(TipoTrabajo $tipoTrabajo)
   {
       $tipoTrabajo->delete();
       return redirect()->route('admin.tipos_trabajo.index')
           ->with('success', 'Tipo de trabajo eliminado exitosamente');
   }
}