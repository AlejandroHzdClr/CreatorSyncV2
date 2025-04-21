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

@if(session('success'))
    <div id="success-message" class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
<div id="todo">
    <div id="principal">
        @foreach($publicaciones as $post)
            <div class="card" style="width: 50%; margin-bottom: 20px;">
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
                    <p class="card-text" style="white-space: pre-wrap;">{{ $post->contenido }}</p>
                </div>
                @if($post->imagen)
                    <img src="{{ asset('storage/' . $post->imagen) }}" 
                        class="card-img-bottom" 
                        style="max-height: 500px; max-width: 100%; min-height: 200px; min-width: 200px; object-fit: contain;" 
                        alt="Imagen de {{ $post->titulo }}">
                @endif
                <div class="card-footer">
                    <img src="{{ asset('images/Comentarios.png') }}" style="width: 25px;" alt="Comentarios">

                    <!-- Formulario para agregar un comentario -->
                    <form class="comentario-form" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control comentario-input" placeholder="Escribe un comentario..." required>
                            <button class="btn btn-primary" type="submit">Comentar</button>
                        </div>
                    </form>

                    <!-- Lista de comentarios -->
                    <div class="comentarios-list" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                        @foreach($post->comentarios as $comentario)
                            <div class="comentario">
                                <strong>{{ $comentario->usuario->nombre }}:</strong> {{ $comentario->contenido }}
                            </div>
                        @endforeach
                    </div>
                    <button class="btn btn-link p-0 like-button" data-publicacion-id="{{ $post->id }}">
                        <img src="{{ Auth::user()->likes->contains('publicacion_id', $post->id) ? asset('images/LikeDado.png') : asset('images/Like.png') }}" 
                            style="width: 25px;" 
                            alt="Me gusta">
                    </button>

                    <!-- Contador de likes -->
                    <span class="like-count" data-publicacion-id="{{ $post->id }}">{{ $post->likes->count() }} Me gusta</span>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Overlay de carga -->
    <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 1050; text-align: center;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p style="margin-top: 10px;">Procesando...</p>
        </div>
    </div>

    <div id="crear">
        <div id="subir">
            <button id="subirX" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subirModal">
                Subir Publicación
            </button>
        </div>
    </div>
</div>

<!-- Modal para subir publicación -->
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

@include('layouts.footer')

<script>
    // Previsualización de la imagen seleccionada
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Ocultar el mensaje de éxito después de 3 segundos
    setTimeout(() => {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s';
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }
    }, 3000);

    $(document).ready(function () {
        // Manejar "Me gusta"
        $('.like-button').on('click', function (event) {
            event.preventDefault();

            const button = $(this);
            const publicacionId = button.data('publicacion-id');
            const likeCountElement = $(`.like-count[data-publicacion-id="${publicacionId}"]`);
            const likeImage = button.find('img');

            const isLiked = likeImage.attr('src').includes('LikeDado.png');
            const url = isLiked ? `/unlike/${publicacionId}` : `/like/${publicacionId}`;

            $('#loading-overlay').fadeIn();

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    const newLikeCount = response.like_count;
                    likeCountElement.text(`${newLikeCount} Me gusta`);
                    likeImage.attr('src', isLiked ? '{{ asset('images/Like.png') }}' : '{{ asset('images/LikeDado.png') }}');
                },
                error: function (xhr) {
                    console.error('Error al procesar el like:', xhr.responseText);
                },
                complete: function () {
                    $('#loading-overlay').fadeOut();
                }
            });
        });

        // Manejar comentarios
        $('.comentario-form').on('submit', function (event) {
            event.preventDefault();

            const form = $(this);
            const publicacionId = form.data('publicacion-id');
            const input = form.find('.comentario-input');
            const contenido = input.val();
            const comentariosList = $(`.comentarios-list[data-publicacion-id="${publicacionId}"]`);

            $('#loading-overlay').fadeIn();

            $.ajax({
                url: `/comentarios/${publicacionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    contenido: contenido,
                },
                success: function (response) {
                    comentariosList.append(`
                        <div class="comentario">
                            <strong>{{ Auth::user()->nombre }}:</strong> ${contenido}
                        </div>
                    `);
                    input.val('');
                },
                error: function (xhr) {
                    console.error('Error al agregar el comentario:', xhr.responseText);
                },
                complete: function () {
                    $('#loading-overlay').fadeOut();
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>