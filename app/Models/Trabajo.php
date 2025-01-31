<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    protected $fillable = [
        'titulo',
        'tipo_trabajo',
        'cliente',
        'direccion',
        'descripcion',
        'presupuesto',
        'worker_id',
        'fecha_programada',
        'estado',
        'encuesta_token',
        'cliente_email'
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    public function tipoTrabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function fotos()
    {
        return $this->hasMany(TrabajoFoto::class);
    }

    public function encuesta()
    {
        return $this->hasOne(Encuesta::class);
    }
}