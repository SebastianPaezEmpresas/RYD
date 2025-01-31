<?php

namespace App\Models;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}