<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreatorSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    .card.mb-3 {
        margin-bottom: 7vw !important;
    }
    .card img {
        max-width: 100%; 
        max-height: 300px;
        object-fit: cover;
    }

    .list-group-item img {
        max-width: 50px; 
        max-height: 50px; 
        object-fit: cover;
    }
</style>
@include('layouts.nav')

<div class="container mt-5">
    <h1>Resultados de Búsqueda</h1>
    <p>Resultados para: <strong>{{ $termino }}</strong></p>

    <!-- Botones de filtro -->
    <div class="mb-4">
        <button class="btn btn-primary me-2" id="filtro-publicaciones">Publicaciones</button>
        <button class="btn btn-secondary" id="filtro-usuarios">Usuarios</button>
    </div>

    <!-- Resultados de Publicaciones -->
    <div id="resultados-publicaciones">
        <h2>Publicaciones</h2>
        @if($publicaciones->isEmpty())
            <p>No se encontraron publicaciones.</p>
        @else
            @foreach($publicaciones as $post)
                <div class="card post-card mb-3" style="width: 50%; margin: auto;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <a href="{{ route('perfil.show', $post->usuario->id) }}">
                                <img src="{{ $post->usuario && $post->usuario->avatar ? asset('storage/' . $post->usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}"
                                     style="width: 75px; border-radius: 50%;"
                                     alt="Imagen de perfil">
                            </a>
                            <p class="card-text">
                                {{ $post->usuario ? $post->usuario->nombre : 'Usuario desconocido' }}
                            </p>
                        </div>
                        <h5 class="card-title">{{ $post->titulo }}</h5>
                        <p class="card-text" style="white-space: pre-wrap; overflow: hidden; max-height: 100px;">{{ Str::limit($post->contenido, 150) }}</p>
                    </div>
                    @if($post->imagen)
                        <img src="{{ asset('storage/' . $post->imagen) }}"
                             class="card-img-bottom"
                             style="max-height: 300px; max-width: 100%; min-height: 100px; min-width: 100px; object-fit: contain;"
                             alt="Imagen de {{ $post->titulo }}">
                    @endif
                    <div class="card-footer">
                        <img src="{{ asset('images/Comentarios.png') }}" style="width: 25px;" alt="Comentarios">
                        <span class="ms-2">{{ $post->comentarios->count() }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Resultados de Usuarios -->
    <div id="resultados-usuarios" style="display: none;">
        <h2>Usuarios</h2>
        @if($usuarios->isEmpty())
            <p>No se encontraron usuarios.</p>
        @else
            <ul class="list-group">
                @foreach($usuarios as $usuario)
                <a href="{{ route('perfil.show', $usuario->id) }}" class="text-decoration-none text-dark">
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ $usuario->avatar ? asset('storage/' . $usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}" 
                            alt="{{ $usuario->nombre }}" 
                            class="rounded-circle me-3" 
                            style="width: 50px; height: 50px;">
                        <div>
                            <strong>{{ $usuario->nombre }}</strong>
                            <p class="mb-0">Seguidores: {{ $usuario->seguidores_count }}</p>
                        </div>
                    </li>
                </a>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {
        // Manejar los botones de filtro
        $('#filtro-publicaciones').on('click', function () {
            $('#resultados-publicaciones').show();
            $('#resultados-usuarios').hide();
            $(this).removeClass('btn-secondary').addClass('btn-primary');
            $('#filtro-usuarios').removeClass('btn-primary').addClass('btn-secondary');
        });

        $('#filtro-usuarios').on('click', function () {
            $('#resultados-usuarios').show();
            $('#resultados-publicaciones').hide();
            $(this).removeClass('btn-secondary').addClass('btn-primary');
            $('#filtro-publicaciones').removeClass('btn-primary').addClass('btn-secondary');
        });
    });
</script>
@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-7+Q1j6x2z5+0e4f
