<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTrabajo extends Model
{
    use HasFactory;

    protected $table = 'tipo_trabajos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_estimada',
        'precio_base'
    ];

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }
}