<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EncuestaPublicController extends Controller
{
    public function show($token)
    {
        $encuesta = Encuesta::where('token', $token)
            ->with('trabajo')
            ->firstOrFail();

        if ($encuesta->estado === 'respondida') {
            return view('encuestas.ya-respondida');
        }

        return view('encuestas.publica', compact('encuesta'));
    }

    public function store(Request $request, $token)
    {
        $encuesta = Encuesta::where('token', $token)->firstOrFail();

        if ($encuesta->estado === 'respondida') {
            return redirect()->back()->with('error', 'Esta encuesta ya ha sido respondida');
        }

        $validatedData = $request->validate([
            'calificacion_general' => 'required|integer|min:1|max:5',
            'puntualidad' => 'required|integer|min:1|max:5',
            'calidad_trabajo' => 'required|integer|min:1|max:5',
            'profesionalismo' => 'required|integer|min:1|max:5',
            'comentarios' => 'nullable|string|max:1000'
        ]);

        $encuesta->update([
            ...$validatedData,
            'estado' => 'respondida',
            'fecha_respuesta' => now()
        ]);

        return redirect()->route('encuesta.gracias');
    }

    public function gracias()
    {
        return view('encuestas.gracias');
    }
}