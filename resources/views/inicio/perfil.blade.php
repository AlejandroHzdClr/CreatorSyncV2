<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - CreatorSync</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
@include('layouts.nav')

<!-- Overlay de carga -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 1050; text-align: center;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <p style="margin-top: 10px;">Procesando...</p>
    </div>
</div>

<div class="body">
    <div id="foto_perfil">
    <img src="{{ $usuario->avatar ? asset('storage/' . $usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}" 
    alt="{{ $usuario->nombre }}" 
    class="foto">
        
        <!-- Mostrar el botón de edición solo si el perfil pertenece al usuario autenticado -->
        @if(Auth::id() === $usuario->id)
        <div id="edicion">
            <img src="{{ asset('images/editar.png') }}" alt="Editar" class="editar">
            <h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                    Editar tu Perfil
                </button>
            </h2>
        </div>
        @endif
    </div>

    <div id="contenido">
        <div id="datos">
            <div id="nombre">
                <h1>{{ $usuario->nombre }}</h1>
                <h2>Seguidores: <span id="seguidores-count">{{ $usuario->seguidores_count }}</span></h2>
            </div>

            <!-- Botón de seguir o dejar de seguir -->
            @if(Auth::id() !== $usuario->id)
                <div id="seguir">
                    @if(Auth::user()->siguiendo->contains('seguido_id', $usuario->id))
                        <button class="btn btn-danger seguir-button" data-usuario-id="{{ $usuario->id }}">Dejar de seguir</button>
                    @else
                        <button class="btn btn-primary seguir-button" data-usuario-id="{{ $usuario->id }}">Seguir</button>
                    @endif
                </div>
            @endif

            <div id="redes_sociales">
                @if($usuario->perfil && !empty($usuario->perfil->redes_sociales))
                    @foreach($usuario->perfil->redes_sociales as $red)
                        @if(is_array($red) && isset($red['nombre'], $red['url']))
                            <a href="{{ $red['url'] }}" target="_blank" class="btn btn-outline-primary mb-2">
                                {{ ucfirst($red['nombre']) }}
                            </a>
                        @endif
                    @endforeach
                @else
                    <p>No hay redes sociales disponibles.</p>
                @endif
            </div>
        </div>

        <div id="biografia">
            <h1>Biografía</h1>
            <p id="contenido_biografia" style="white-space: pre-wrap;">{{ $usuario->descripcion ?? 'No hay biografía disponible.' }}</p>
        </div>

        <!-- Todos los Posts -->
        <div id="publicaciones" class="container mt-5" style="margin-bottom:10%;">
            <h1 class="text-center mb-4">Publicaciones</h1>
            @forelse($publicaciones as $post)
                <div class="card mx-auto mb-4" style="max-width: 600px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $post->titulo }}</h5>
                        <p class="card-text" style="white-space: pre-wrap;">{{ $post->contenido }}</p>
                    </div>
                    @if($post->imagen)
                        <img src="{{ asset('storage/' . $post->imagen) }}" 
                            class="card-img-top" 
                            style="max-height: 400px; object-fit: cover; border-radius: 0 0 10px 10px;" 
                            alt="Imagen de {{ $post->titulo }}">
                    @endif
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-link p-0 like-button" data-publicacion-id="{{ $post->id }}">
                                <img src="{{ Auth::user()->likes->contains('publicacion_id', $post->id) ? asset('images/LikeDado.png') : asset('images/Like.png') }}" 
                                    style="width: 25px;" 
                                    alt="Me gusta">
                            </button>
                            <span class="like-count" data-publicacion-id="{{ $post->id }}">{{ $post->likes->count() }} Me gusta</span>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#comentariosModal-{{ $post->id }}">
                            Ver Comentarios
                        </button>
                    </div>
                </div>

                <!-- Modal de comentarios -->
                <div class="modal fade" id="comentariosModal-{{ $post->id }}" tabindex="-1" aria-labelledby="comentariosModalLabel-{{ $post->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="comentariosModalLabel-{{ $post->id }}">{{ $post->titulo }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ $post->contenido }}</p>
                                @if($post->imagen)
                                    <img src="{{ asset('storage/' . $post->imagen) }}" class="img-fluid" alt="Imagen de {{ $post->titulo }}">
                                @endif
                                <hr>
                                <h5>Comentarios</h5>
                                <div class="comentarios-list" data-publicacion-id="{{ $post->id }}">
                                    @foreach($post->comentarios as $comentario)
                                        <div class="comentario mb-2">
                                            <strong>{{ $comentario->usuario->nombre }}:</strong> {{ $comentario->contenido }}
                                        </div>
                                    @endforeach
                                </div>
                                <form class="comentario-form mt-3" data-publicacion-id="{{ $post->id }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control comentario-input" placeholder="Escribe un comentario..." required>
                                        <button class="btn btn-primary" type="submit">Comentar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No hay publicaciones disponibles.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Manejar "Seguir" y "Dejar de seguir"
        $('.seguir-button').on('click', function () {
            const button = $(this);
            const usuarioId = button.data('usuario-id');
            const esSeguir = button.hasClass('btn-primary'); // Si tiene la clase btn-primary, es "Seguir"

            // URL para seguir o dejar de seguir
            const url = esSeguir ? `/seguir/${usuarioId}` : `/dejar-de-seguir/${usuarioId}`;

            // Mostrar el overlay de carga
            $('#loading-overlay').fadeIn();

            // Enviar la solicitud AJAX
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Actualizar el botón según la acción realizada
                    if (esSeguir) {
                        button.removeClass('btn-primary').addClass('btn-danger').text('Dejar de seguir');
                    } else {
                        button.removeClass('btn-danger').addClass('btn-primary').text('Seguir');
                    }

                    // Actualizar el contador de seguidores si se incluye en la respuesta
                    if (response.seguidores_count !== undefined) {
                        $('#seguidores-count').text(response.seguidores_count);
                    }
                },
                error: function (xhr) {
                    console.error('Error al procesar la solicitud:', xhr.responseText);
                },
                complete: function () {
                    // Ocultar el overlay de carga
                    $('#loading-overlay').fadeOut();
                }
            });
        });
        
        // Manejar "Me gusta"
        $('.like-button').on('click', function () {
            const button = $(this);
            const publicacionId = button.data('publicacion-id');
            const likeCountElement = $(`.like-count[data-publicacion-id="${publicacionId}"]`);
            const likeImage = button.find('img');

            const isLiked = likeImage.attr('src').includes('LikeDado.png');
            const url = isLiked ? `/unlike/${publicacionId}` : `/like/${publicacionId}`;

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    likeCountElement.text(`${response.like_count} Me gusta`);
                    likeImage.attr('src', isLiked ? '{{ asset('images/Like.png') }}' : '{{ asset('images/LikeDado.png') }}');
                },
                error: function (xhr) {
                    console.error('Error al procesar el like:', xhr.responseText);
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

            $.ajax({
                url: `/comentarios/${publicacionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    contenido: contenido,
                },
                success: function (response) {
                    comentariosList.append(`
                        <div class="comentario mb-2">
                            <strong>{{ Auth::user()->nombre }}:</strong> ${contenido}
                        </div>
                    `);
                    input.val('');
                },
                error: function (xhr) {
                    console.error('Error al agregar el comentario:', xhr.responseText);
                }
            });
        });
    });
</script>

@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>