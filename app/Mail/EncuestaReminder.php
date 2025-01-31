<?php
namespace App\Mail;

use App\Models\Trabajo;
use Illuminate\Mail\Mailable;

class EncuestaReminder extends Mailable
{
    public function __construct(public Trabajo $trabajo){}

    public function build()
    {
        return $this->markdown('emails.encuesta-reminder')
            ->subject('¿Cómo calificaría nuestro servicio?');
    }
}