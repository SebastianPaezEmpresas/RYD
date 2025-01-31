<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
   use Notifiable;

   protected $fillable = [
       'name',
       'email', 
       'password',
       'role',
       'active'
   ];

   protected $hidden = [
       'password',
       'remember_token',
   ];

   protected $casts = [
       'active' => 'boolean',
   ];

   public function trabajos()
   {
       return $this->hasMany(Trabajo::class, 'worker_id');
   }

   public function fotosTrabajos()
   {
       return $this->hasManyThrough(TrabajoFoto::class, Trabajo::class, 'worker_id');
   }

   public function isAdmin()
   {
       return $this->role === 'admin';
   }

   public function isWorker() 
   {
       return $this->role === 'worker';
   }
}