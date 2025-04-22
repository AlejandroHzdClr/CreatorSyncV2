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
            <div class="card post-card" style="width: 50%; margin-bottom: 20px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
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
                    <button class="btn btn-link p-0 like-button" data-publicacion-id="{{ $post->id }}">
                        <img src="{{ Auth::user()->likes->contains('publicacion_id', $post->id) ? asset('images/LikeDado.png') : asset('images/Like.png') }}"
                             style="width: 25px;"
                             alt="Me gusta">
                    </button>
                    <span class="like-count" data-publicacion-id="{{ $post->id }}">{{ $post->likes->count() }} Me gusta</span>

                    <form class="comentario-form" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control comentario-input" placeholder="Escribe un comentario..." required>
                            <button class="btn btn-primary" type="submit">Comentar</button>
                        </div>
                    </form>

                    <div class="comentarios-list" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                        @if($post->comentarios->count() > 0)
                            @foreach($post->comentarios->take(3) as $comentario)
                                <div class="comentario">
                                    <strong>{{ $comentario->usuario->nombre }}:</strong> {{ $comentario->contenido }}
                                </div>
                            @endforeach
                            @if($post->comentarios->count() > 3)
                                <div class="ver-mas-comentarios" style="cursor: pointer; color: blue; margin-top: 5px;">Ver más comentarios...</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1" aria-labelledby="postModalLabel{{ $post->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="postModalLabel{{ $post->id }}">{{ $post->titulo }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('perfil.show', $post->usuario->id) }}">
                                        <img src="{{ $post->usuario && $post->usuario->avatar ? asset('storage/' . $post->usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}"
                                             style="width: 75px; border-radius: 50%; margin-right: 10px;"
                                             alt="Imagen de perfil">
                                    </a>
                                    <p class="mb-0">
                                        {{ $post->usuario ? $post->usuario->nombre : 'Usuario desconocido' }}
                                    </p>
                                </div>
                            </div>
                            <p style="white-space: pre-wrap;">{{ $post->contenido }}</p>
                            @if($post->imagen)
                                <img src="{{ asset('storage/' . $post->imagen) }}"
                                     class="img-fluid mb-3"
                                     alt="Imagen de {{ $post->titulo }}">
                            @endif

                            <div class="mb-3">
                                <img src="{{ asset('images/Comentarios.png') }}" style="width: 25px; margin-right: 5px;" alt="Comentarios">
                                <span>Comentarios</span>
                                <div class="comentarios-list-modal" data-publicacion-id="{{ $post->id }}">
                                    @foreach($post->comentarios as $comentario)
                                        <div class="comentario mt-2">
                                            <strong>{{ $comentario->usuario->nombre }}:</strong> {{ $comentario->contenido }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <form class="comentario-form-modal" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control comentario-input-modal" placeholder="Escribe un comentario..." required>
                                    <button class="btn btn-primary" type="submit">Comentar</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-link p-0 like-button" data-publicacion-id="{{ $post->id }}">
                                <img src="{{ Auth::user()->likes->contains('publicacion_id', $post->id) ? asset('images/LikeDado.png') : asset('images/Like.png') }}"
                                     style="width: 25px; margin-right: 5px;"
                                     alt="Me gusta">
                            </button>
                            <span class="like-count" data-publicacion-id="{{ $post->id }}">{{ $post->likes->count() }} Me gusta</span>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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

<div class="modal fade" id="subirModal" tabindex="-1" aria-labelledby="subirModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subirModalLabel">Crear Nueva Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('inicio.store') }}" method="POST" enctype="multipart/form-data" id="subirForm">
                    @csrf
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título de la Publicación</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Escribe un título atractivo" required>
                    </div>
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="5" placeholder="¿Qué quieres compartir?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir Imagen (Opcional)</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event)">
                        <div id="image-preview-container" style="margin-top: 1rem; display: none;">
                            <img id="image-preview" src="#" alt="Vista previa de la imagen" style="max-width: 100%; height: auto; max-height: 200px;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="subirForm" class="btn btn-success">Publicar</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

<script>
    // Previsualización de la imagen seleccionada
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block'; // Mostrar el contenedor
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#'; // Limpiar la vista previa
            previewContainer.style.display = 'none'; // Ocultar el contenedor si no hay imagen
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
        // Manejar "Me gusta" (para la vista principal y el modal)
        $('.like-button').on('click', function (event) {
            event.preventDefault();

            const button = $(this);
            const publicacionId = button.data('publicacion-id');
            const likeCountElements = $(`.like-count[data-publicacion-id="${publicacionId}"]`);
            const likeImages = $(`img[src*='Like'][data-publicacion-id="${publicacionId}"]`);

            const isLiked = likeImages.first().attr('src').includes('LikeDado.png');
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
                    likeCountElements.text(`${newLikeCount} Me gusta`);
                    likeImages.each(function() {
                        $(this).attr('src', isLiked ? '{{ asset('images/Like.png') }}' : '{{ asset('images/LikeDado.png') }}');
                    });
                },
                error: function (xhr) {
                    console.error('Error al procesar el like:', xhr.responseText);
                },
                complete: function () {
                    $('#loading-overlay').fadeOut();
                }
            });
        });

        // Manejar comentarios (para el formulario FUERA del modal)
        $('.comentario-form').on('submit', function (event) {
            event.preventDefault();

            const form = $(this);
            const publicacionId = form.data('publicacion-id');
            const input = form.find('.comentario-input');
            const contenido = input.val();
            const comentariosListPrincipal = $(`.comentarios-list[data-publicacion-id="${publicacionId}"]`);
            const comentariosListModal = $(`.comentarios-list-modal[data-publicacion-id="${publicacionId}"]`);

            $('#loading-overlay').fadeIn();

            $.ajax({
                url: `/comentarios/${publicacionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    contenido: contenido,
                },
                success: function (response) {
                    // Agregar el comentario a la lista principal (si no se han mostrado todos)
                    if (comentariosListPrincipal.find('.comentario').length < 3 || comentariosListPrincipal.find('.ver-mas-comentarios').length > 0) {
                        comentariosListPrincipal.prepend(`
                            <div class="comentario">
                                <strong>{{ Auth::user()->nombre }}:</strong> ${contenido}
                            </div>
                        `);
                        // Si ahora hay más de 3, mostrar el "Ver más..." si no está ya
                        const comentariosCount = comentariosListPrincipal.find('.comentario').length;
                        if (comentariosCount > 3 && comentariosListPrincipal.find('.ver-mas-comentarios').length === 0) {
                            comentariosListPrincipal.append('<div class="ver-mas-comentarios" style="cursor: pointer; color: blue; margin-top: 5px;">Ver más comentarios...</div>');
                            // Ocultar los comentarios extra
                            comentariosListPrincipal.find('.comentario:gt(2)').hide();
                        } else if (comentariosCount <= 3 && comentariosListPrincipal.find('.ver-mas-comentarios').length > 0) {
                            comentariosListPrincipal.find('.ver-mas-comentarios').remove();
                            comentariosListPrincipal.find('.comentario').show();
                        }
                    }

                    // Agregar el comentario a la lista del modal
                    comentariosListModal.prepend(`
                        <div class="comentario mt-2">
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

        // Manejar comentarios en el modal (formulario DENTRO del modal)
        $('.comentario-form-modal').on('submit', function (event) {
            event.preventDefault();

            const form = $(this);
            const publicacionId = form.data('publicacion-id');
            const input = form.find('.comentario-input-modal');
            const contenido = input.val();
            const comentariosListPrincipal = $(`.comentarios-list[data-publicacion-id="${publicacionId}"]`);
            const comentariosListModal = $(`.comentarios-list-modal[data-publicacion-id="${publicacionId}"]`);

            $('#loading-overlay').fadeIn();

            $.ajax({
                url: `/comentarios/${publicacionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    contenido: contenido,
                },
                success: function (response) {
                    // Agregar el comentario a la lista principal (si no se han mostrado todos)
                    if (comentariosListPrincipal.find('.comentario').length < 3 || comentariosListPrincipal.find('.ver-mas-comentarios').length > 0) {
                        comentariosListPrincipal.prepend(`
                            <div class="comentario">
                                <strong>{{ Auth::user()->nombre }}:</strong> ${contenido}
                            </div>
                        `);
                        // Si ahora hay más de 3, mostrar el "Ver más..." si no está ya
                        const comentariosCount = comentariosListPrincipal.find('.comentario').length;
                        if (comentariosCount > 3 && comentariosListPrincipal.find('.ver-mas-comentarios').length === 0) {
                            comentariosListPrincipal.append('<div class="ver-mas-comentarios" style="cursor: pointer; color: blue; margin-top: 5px;">Ver más comentarios...</div>');
                            // Ocultar los comentarios extra
                            comentariosListPrincipal.find('.comentario:gt(2)').hide();
                        } else if (comentariosCount <= 3 && comentariosListPrincipal.find('.ver-mas-comentarios').length > 0) {
                            comentariosListPrincipal.find('.ver-mas-comentarios').remove();
                            comentariosListPrincipal.find('.comentario').show();
                        }
                    }

                    // Agregar el comentario a la lista del modal
                    comentariosListModal.prepend(`
                        <div class="comentario mt-2">
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

        // Mostrar solo los primeros 3 comentarios y un indicador en la vista principal
        $('.card').each(function() {
            const comentariosList = $(this).find('.comentarios-list');
            const comentarios = comentariosList.find('.comentario');
            if (comentarios.length > 3) {
                comentarios.slice(3).hide();
                if (comentariosList.find('.ver-mas-comentarios').length === 0) {
                    comentariosList.append('<div class="ver-mas-comentarios" style="cursor: pointer; color: blue; margin-top: 5px;">Ver más comentarios...</div>');
                }
            }
        });

        // Evento para mostrar todos los comentarios al hacer clic en "Ver más comentarios..."
        $(document).on('click', '.ver-mas-comentarios', function() {
            const comentariosList = $(this).siblings('.comentarios-list');
            comentariosList.find('.comentario:hidden').show();
            $(this).remove();
        });

        // Al abrir el modal, asegurar que todos los comentarios estén visibles
        $('.post-card').on('click', function() {
            const publicacionId = $(this).data('bs-target').replace('#postModal', '');
            const comentariosListModal = $(`.comentarios-list-modal[data-publicacion-id="${publicacionId}"]`);
            comentariosListModal.find('.comentario').show();
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>