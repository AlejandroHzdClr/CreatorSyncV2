<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CreatorSync</title>
    <link href="{{ asset('css/inicio.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
@include('layouts.nav')
<div id="todo">
    <div id="principal">
        @foreach($publicaciones as $post)
            <x-post :post="$post" />
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

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear nueva publicación -->
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
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título">
                    </div>
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="5" placeholder="¿Qué quieres compartir?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir Imagen</label>
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

    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.style.display = 'none';
        }
    }

    $(document).ready(function () {
        // Manejar "Me gusta" (para la vista principal y el modal)
        $(document).on('click', '.like-button', function (event) {
            event.preventDefault();

            const button = $(this);
            const publicacionId = button.data('publicacion-id');
            const likeCountElements = $(`.like-count[data-publicacion-id="${publicacionId}"]`);
            const likeImage = button.find('img'); // Busca la imagen dentro del botón

            // Verifica si la imagen existe antes de acceder a su atributo src
            if (!likeImage.length) {
                console.error('No se encontró la imagen de "Me gusta" para la publicación con ID:', publicacionId);
                return;
            }

            const isLiked = likeImage.attr('src').includes('LikeDado.png');
            const url = isLiked ? `/unlike/${publicacionId}` : `/like/${publicacionId}`;

            // Mostrar el overlay de carga
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




        // Quitar el mensaje de success después de 3 segundos
        setTimeout(() => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.transition = 'opacity 0.5s';
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 500);
            }
        }, 3000);

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

        // Solución para el problema de aria-hidden
        $('.modal').on('shown.bs.modal', function () {
            $(this).attr('aria-hidden', 'false'); // Asegúrate de que aria-hidden sea false al abrir el modal
        });

        $('.modal').on('hidden.bs.modal', function () {
            $(this).attr('aria-hidden', 'true'); // Restablece aria-hidden a true al cerrar el modal
        });

        $(document).on('click', '.follow-button', function (event) {
            event.preventDefault();

            const button = $(this);
            const usuarioId = button.data('usuario-id');
            const isFollowing = button.data('following') === 'true';
            const url = isFollowing 
                ? `/dejarDeSeguir/${usuarioId}` 
                : `/seguir/${usuarioId}`;

            // Mostrar un overlay de carga si es necesario
            $('#loading-overlay').fadeIn();

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Actualizar el estado del botón
                    button.data('following', !isFollowing);
                    button.text(!isFollowing ? 'Parar de seguir' : 'Seguir');
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
    });

    function confirmDelete(actionUrl) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = actionUrl; // Establece la URL de acción del formulario
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show(); // Muestra el modal
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>