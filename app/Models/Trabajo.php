<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'trabajador_id',
        'tipo_trabajo_id',
        'link_compartido',
        'calificacion',
        'comentario_cliente',
        'evidencias_inicio',
        'evidencias_fin',
        'notas_finales',
        'cliente_email'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'evidencias_inicio' => 'array',
        'evidencias_fin' => 'array'
    ];

    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }

    public function tipoTrabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    public function getEstadoColorAttribute()
    {
        return [
            'pendiente' => 'border-yellow-500',
            'en_progreso' => 'border-blue-500',
            'completado' => 'border-green-500',
        ][$this->estado] ?? 'border-gray-500';
    }

    public function getEstadoBgColorAttribute()
    {
        return [
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'en_progreso' => 'bg-blue-100 text-blue-800',
            'completado' => 'bg-green-100 text-green-800',
        ][$this->estado] ?? 'bg-gray-100 text-gray-800';
    }
}