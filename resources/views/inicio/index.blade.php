<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreatorSync</title>
    <link href="{{ asset('css/inicio.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
@include('layouts.nav')

    <div id="todo">
        <div id="opciones">
            <ul class="list-group">
                <li class="list-group-item" onclick=window.location.href="./inicio.php">Inicio</li>
                <li class="list-group-item">Posts</li>
                <li class="list-group-item">Eventos</li>
                <li class="list-group-item d-flex justify-content-between align-items-center" onclick=window.location.href="./notificaciones.php">
                    Notificaciones
                   <!--<span class="badge text-bg-primary rounded-pill">11</span> -->
                </li>
                <li class="list-group-item" onclick=window.location.href="./configuracion.php">Ajustes</li>
            </ul>
        </div>
        <div id="principal">
            @foreach($publicaciones as $post)
                <div class="card" style="width: 50%; margin-bottom: 20px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <img src="{{ $post->usuario && $post->usuario->avatar ? asset($post->usuario->avatar) : asset('images/PerfilPredeterminado.jpg')}}" style="width: 75px; border-radius: 50%;" alt="Imagen de {{ $post->titulo }}">
                            <p class="card-text">{{ $post->usuario ? $post->usuario->nombre : 'Usuario desconocido' }}</p>
                        </div>
                        <h5 class="card-title">{{ $post->titulo }}</h5>
                        <p class="card-text">{{ $post->contenido }}</p>
                    </div>
                    <img src="{{ $post->imagen }}" class="card-img-bottom" style="max-height: 500px; width: auto; height: auto; object-fit: contain;" alt="Imagen de {{ $post->titulo }}">
                    <div class="card-footer">
                        <img src="{{ asset('images/Comentarios.png') }}" style="width: 25px;" alt="Comentarios">
                        <img src="{{ asset('images/Like.png') }}" style="width: 25px;" alt="Me gusta">
                    </div>
                </div>
            @endforeach
        </div>
        <div id="crear">
            <div id="subir">
                <button id="subirX" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subirModal">
                    Subir Publicación
                </button>
            </div>
            <div id="generos">
                <ul class="list-group">
                    <li class="list-group-item">Juegos</li>
                    <li class="list-group-item">Fitness</li>
                    <li class="list-group-item">Arte</li>
                    <li class="list-group-item">Just Talking</li>
                    <li class="list-group-item">Noticias</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="subirModal" tabindex="-1" aria-labelledby="subirModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subirModalLabel">Subir Publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('inicio.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="contenido" class="form-label">Descripción</label>
                            <textarea class="form-control" id="contenido" name="contenido" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-success">Subir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
