<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabajoMaterial extends Model
{
    protected $fillable = [
        'trabajo_id',
        'nombre',
        'cantidad',
        'precio_unitario'
    ];

    protected $casts = [
    'precio_unitario' => 'decimal:2'
];
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}