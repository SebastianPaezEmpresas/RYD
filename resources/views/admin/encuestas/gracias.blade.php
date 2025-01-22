{{-- resources/views/encuestas/gracias.blade.php --}}
@extends('layouts.public')

@section('title', '¡Gracias por su Respuesta!')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-check-circle text-success mb-4" style="font-size: 64px;"></i>
                    <h2 class="card-title mb-4">¡Gracias por su Respuesta!</h2>
                    <p class="card-text mb-4">
                        Su opinión es muy importante para nosotros y nos ayuda a mejorar nuestros servicios.
                        Agradecemos el tiempo que ha dedicado a completar esta encuesta.
                    </p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection