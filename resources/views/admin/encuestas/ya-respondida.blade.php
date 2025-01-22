@extends('layouts.public')

@section('title', 'Encuesta ya Respondida')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-info-circle text-info mb-4" style="font-size: 64px;"></i>
                    <h2 class="card-title mb-4">Esta Encuesta ya fue Respondida</h2>
                    <p class="card-text mb-4">
                        La encuesta que intenta acceder ya ha sido completada anteriormente.
                        Solo se permite una respuesta por encuesta.
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