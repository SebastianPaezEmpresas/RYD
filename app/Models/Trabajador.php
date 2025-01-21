<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'trabajadores';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'estado',
        'fecha_contratacion',
        'salario',
        'cargo',
        'observaciones'
    ];

    protected $casts = [
        'fecha_contratacion' => 'date'
    ];

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }
}