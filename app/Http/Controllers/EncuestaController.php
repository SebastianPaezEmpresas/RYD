<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Trabajo;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
   public function index()
   {
       $encuestas = Encuesta::with('trabajo')->get();
       return view('admin.encuestas.index', compact('encuestas'));
   }

   public function show(Encuesta $encuesta)
   {
       return view('admin.encuestas.show', compact('encuesta'));
   }

   public function create($token)
   {
       $trabajo = Trabajo::where('encuesta_token', $token)
                        ->whereDoesntHave('encuesta')
                        ->firstOrFail();
                        
       return view('encuestas.create', compact('trabajo'));
   }

   public function store(Request $request, $token)
   {
       $trabajo = Trabajo::where('encuesta_token', $token)
                        ->whereDoesntHave('encuesta')
                        ->firstOrFail();

       $validated = $request->validate([
           'calificacion' => 'required|integer|min:1|max:5',
           'comentario' => 'nullable|string',
           'sugerencias' => 'nullable|string',
           'recomendaria' => 'required|boolean'
       ]);

       $trabajo->encuesta()->create($validated);

       return redirect()->route('encuestas.gracias');
   }

   public function gracias()
   {
       return view('encuestas.gracias');
   }
}