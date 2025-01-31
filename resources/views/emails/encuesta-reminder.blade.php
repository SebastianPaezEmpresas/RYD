@component('mail::message')
# Evalúe Nuestro Servicio

Gracias por confiar en RYD Jardinería. Nos gustaría conocer su opinión sobre el servicio realizado.

@component('mail::button', ['url' => route('encuestas.create', $trabajo->encuesta_token)])
Evaluar Servicio
@endcomponent

Su opinión es muy importante para nosotros.
@endcomponent