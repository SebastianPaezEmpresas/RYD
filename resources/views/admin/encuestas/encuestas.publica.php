@extends('layouts.public')

@section('title', 'Encuesta de Satisfacción')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Encuesta de Satisfacción - RYD Jardinería</h4>
                </div>
                <div class="card-body">
                    <!-- Información del Trabajo -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="card-title">Trabajo Realizado</h5>
                        <p class="card-text">
                            <strong>Descripción:</strong> {{ $encuesta->trabajo->descripcion }}<br>
                            <strong>Fecha:</strong> {{ $encuesta->trabajo->fecha_realizacion->format('d/m/Y') }}<br>
                            <strong>Cliente:</strong> {{ $encuesta->trabajo->cliente->nombre }}
                        </p>
                    </div>

                    <form action="{{ route('encuestas.responder', $encuesta->token) }}" method="POST" id="encuestaForm">
                        @csrf
                        
                        <!-- Puntualidad -->
                        <div class="mb-4">
                            <label class="form-label">1. ¿Cómo calificaría nuestra puntualidad?</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="puntualidad{{ $i }}" name="puntualidad" value="{{ $i }}" required>
                                <label for="puntualidad{{ $i }}" title="{{ $i }} estrellas">
                                    <i class="fas fa-star"></i>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Calidad -->
                        <div class="mb-4">
                            <label class="form-label">2. ¿Cómo calificaría la calidad de nuestro trabajo?</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="calidad{{ $i }}" name="calidad" value="{{ $i }}" required>
                                <label for="calidad{{ $i }}" title="{{ $i }} estrellas">
                                    <i class="fas fa-star"></i>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Atención -->
                        <div class="mb-4">
                            <label class="form-label">3. ¿Cómo calificaría la atención de nuestro personal?</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="atencion{{ $i }}" name="atencion" value="{{ $i }}" required>
                                <label for="atencion{{ $i }}" title="{{ $i }} estrellas">
                                    <i class="fas fa-star"></i>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Limpieza -->
                        <div class="mb-4">
                            <label class="form-label">4. ¿Cómo calificaría la limpieza post-trabajo?</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="limpieza{{ $i }}" name="limpieza" value="{{ $i }}" required>
                                <label for="limpieza{{ $i }}" title="{{ $i }} estrellas">
                                    <i class="fas fa-star"></i>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Recomendación -->
                        <div class="mb-4">
                            <label class="form-label">5. ¿Recomendaría nuestros servicios?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomienda" id="recomiendaSi" value="1" required>
                                <label class="form-check-label" for="recomiendaSi">
                                    Sí, lo recomendaría
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomienda" id="recomiendaNo" value="0">
                                <label class="form-check-label" for="recomiendaNo">
                                    No lo recomendaría
                                </label>
                            </div>
                        </div>

                        <!-- Comentarios -->
                        <div class="mb-4">
                            <label for="comentarios" class="form-label">6. ¿Tiene algún comentario adicional?</label>
                            <textarea class="form-control" id="comentarios" name="comentarios" rows="3" placeholder="Sus comentarios nos ayudan a mejorar..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Enviar Encuesta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    padding: 5px;
    font-size: 25px;
    color: #ddd;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffd700;
}

.star-rating label:hover:before,
.star-rating label:hover ~ label:before,
.star-rating input:checked ~ label:before {
    content: '\f005';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Validación del formulario
    $('#encuestaForm').submit(function(e) {
        const required = ['puntualidad', 'calidad', 'atencion', 'limpieza', 'recomienda'];
        let valid = true;

        required.forEach(function(field) {
            if (!$(`input[name="${field}"]:checked`).length) {
                valid = false;
                $(`input[name="${field}"]`).first().closest('.mb-4').addClass('has-error');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Por favor complete todas las preguntas antes de enviar la encuesta.');
        }
    });
});
</script>
@endpush