<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'fecha_realizacion',
        'trabajador_id',
        'tipo_trabajo_id',
        'link_compartido',
        'token',  // Agregado token
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
        'fecha_realizacion' => 'datetime',
        'evidencias_inicio' => 'array',
        'evidencias_fin' => 'array'
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }

    public function tipoTrabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function encuesta()
    {
        return $this->hasOne(Encuesta::class);
    }

    public function getEventColorAttribute()
    {
        return [
            'pendiente' => '#EAB308', // yellow-500
            'en_progreso' => '#3B82F6', // blue-500
            'completado' => '#22C55E', // green-500
        ][$this->estado] ?? '#6B7280'; // gray-500
    }

    public function toCalendarEvent()
    {
        return [
            'id' => $this->id,
            'title' => $this->titulo,
            'start' => $this->fecha_inicio->format('Y-m-d\TH:i:s'),
            'end' => $this->fecha_fin ? $this->fecha_fin->format('Y-m-d\TH:i:s') : null,
            'backgroundColor' => $this->event_color,
            'borderColor' => $this->event_color,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'descripcion' => $this->descripcion,
                'estado' => $this->estado,
                'trabajador' => $this->trabajador ? $this->trabajador->nombre . ' ' . $this->trabajador->apellido : 'Sin asignar',
                'cliente' => $this->cliente ? $this->cliente->nombre : 'Sin cliente',
                'cliente_email' => $this->cliente_email,
                'fecha_realizacion' => $this->fecha_realizacion ? $this->fecha_realizacion->format('Y-m-d') : null
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($trabajo) {
            if (!$trabajo->token) {
                $trabajo->token = Str::random(40);
            }
        });
    }
}