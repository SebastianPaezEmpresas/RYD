<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $fillable = [
        'trabajo_id',
        'puntuacion',
        'comentario',
        'completada'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}