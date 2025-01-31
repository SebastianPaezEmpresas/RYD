<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabajoFoto extends Model
{
   protected $table = 'trabajo_fotos';
   
   protected $fillable = [
       'trabajo_id',
       'ruta',
       'etapa',
       'fecha_captura'
   ];

   protected $casts = [
       'fecha_captura' => 'datetime'
   ];

   public function trabajo()
   {
       return $this->belongsTo(Trabajo::class);
   }
}