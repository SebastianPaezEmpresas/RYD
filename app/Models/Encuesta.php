<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Encuesta extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trabajo_id',
        'token',
        'calificacion_general',
        'puntualidad',
        'calidad_trabajo',
        'profesionalismo',
        'comentarios',
        'fecha_envio',
        'fecha_respuesta',
        'estado'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_respuesta' => 'datetime',
        'calificacion_general' => 'integer',
        'puntualidad' => 'integer',
        'calidad_trabajo' => 'integer',
        'profesionalismo' => 'integer',
    ];

    

    protected $attributes = [
        'estado' => 'pendiente'
    ];

    // Relaciones
    public function trabajo()
{
    return $this->belongsTo(Trabajo::class);
}

public function historial_envios()
{
    return $this->hasMany(HistorialEnvio::class);
}

    
    // Scopes para filtrar
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnviadas($query)
    {
        return $query->where('estado', 'enviada');
    }

    public function scopeRespondidas($query)
    {
        return $query->where('estado', 'respondida');
    }

    
    // Atributo calculado para el promedio de calificaciones
    public function getPromedioCalificacionesAttribute()
    {
        $calificaciones = array_filter([
            $this->calificacion_general,
            $this->puntualidad,
            $this->calidad_trabajo,
            $this->profesionalismo
        ]);
        
        return count($calificaciones) > 0 ? array_sum($calificaciones) / count($calificaciones) : 0;
    }
}