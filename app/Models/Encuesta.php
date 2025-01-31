<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $fillable = [
        'trabajo_id',
        'calificacion',
        'comentario',
        'sugerencias',
        'recomendaria'
    ];

    protected $casts = [
        'recomendaria' => 'boolean'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}