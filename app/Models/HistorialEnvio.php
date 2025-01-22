<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEnvio extends Model
{
    protected $fillable = [
        'encuesta_id',
        'tipo',
        'email',
        'estado',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}