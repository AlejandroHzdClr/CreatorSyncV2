$(document).ready(function () {
    // Funcionalidad de "Me gusta"
    $(document).on('click', '.like-button', function (event) {
        event.preventDefault();

        const button = $(this);
        const publicacionId = button.data('publicacion-id');
        const likeCountElements = $(`.like-count[data-publicacion-id="${publicacionId}"]`);
        const likeImage = button.find('img');

        const isLiked = likeImage.attr('src').includes('LikeDado.png');
        const url = isLiked ? `/unlike/${publicacionId}` : `/like/${publicacionId}`;

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                const newLikeCount = response.like_count;
                likeCountElements.text(`${newLikeCount} Me gusta`);
                likeImage.attr('src', isLiked ? '/images/Like.png' : '/images/LikeDado.png');
            },
            error: function (xhr) {
                console.error('Error al procesar el like:', xhr.responseText);
            }
        });
    });

    // Funcionalidad de "Seguir/Parar de Seguir"
    $(document).on('click', '.follow-button', function (event) {
        event.preventDefault();

        const button = $(this);
        const usuarioId = button.data('usuario-id');
        const isFollowing = button.data('following') === 'true';
        const url = isFollowing ? `/dejarDeSeguir/${usuarioId}` : `/seguir/${usuarioId}`;

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                button.data('following', !isFollowing);
                button.text(!isFollowing ? 'Parar de seguir' : 'Seguir');
            },
            error: function (xhr) {
                console.error('Error al procesar la solicitud de seguir:', xhr.responseText);
            }
        });
    });

    // Funcionalidad de "Comentarios"
    $('.comentario-form').off('submit').on('submit', function (event) {
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
                _token: $('meta[name="csrf-token"]').attr('content'),
                contenido: contenido,
            },
            success: function (response) {
                // Generar el HTML del nuevo comentario
                const nuevoComentario = `
                    <div class="comentario mb-2" id="comentario-${response.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <!-- Imagen del usuario -->
                                <a href="/perfil/${response.usuario_id}" class="me-2">
                                    <img src="${response.usuario_avatar}" 
                                        alt="Imagen de ${response.usuario_nombre}" 
                                        style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                </a>
                                <!-- Nombre y contenido del comentario -->
                                <strong>${response.usuario_nombre}:</strong> ${response.contenido}
                            </div>
                            <!-- Menú desplegable -->
                            <div class="dropdown">
                                <button class="btn btn-link p-0 text-dark" type="button" id="dropdownMenuButtonComentario${response.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="/images/3puntos.png" alt="3puntos" style="width: 20px;">
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="dropdownMenuButtonComentario${response.id}">
                                    <li>
                                        <a class="dropdown-item" href="/perfil/${response.usuario_id}">Ver perfil</a>
                                    </li>
                                    ${response.puede_eliminar ? `
                                    <li>
                                        <button class="dropdown-item delete-comment-button" data-comentario-id="${response.id}">Eliminar</button>
                                    </li>` : ''}
                                    <li>
                                        <button class="dropdown-item" onclick="copiarContenido('${response.contenido}')">Copiar contenido</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
    
                // Agregar el nuevo comentario a la lista
                comentariosList.append(nuevoComentario);
                input.val(''); // Limpiar el campo de entrada
            },
            error: function (xhr) {
                console.error('Error al agregar el comentario:', xhr.responseText);
            },
            complete: function () {
                $('#loading-overlay').fadeOut();
            }
        });
    });

    // Funcionalidad de "Eliminar Comentarios"
    $(document).on('click', '.delete-comment-button', function (event) {
        event.preventDefault();

        const button = $(this);
        const comentarioId = button.data('comentario-id');
        const comentarioElement = $(`#comentario-${comentarioId}`);

        $.ajax({
            url: `/comentarios/${comentarioId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                comentarioElement.remove();
            },
            error: function (xhr) {
                console.error('Error al eliminar el comentario:', xhr.responseText);
            }
        });
    });

    $(document).ajaxStart(function () {
        $('#loading-overlay').fadeIn();
    });

    $(document).ajaxStop(function () {
        $('#loading-overlay').fadeOut();
    });
});

function confirmDelete(actionUrl) {
    // Cerrar otros modales abiertos
    const openModals = document.querySelectorAll('.modal.show');
    openModals.forEach(openModal => {
        const bootstrapModal = bootstrap.Modal.getInstance(openModal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    });

    // Configurar la acción del formulario de eliminación
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = actionUrl;

    // Abrir el modal de confirmación
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    confirmDeleteModal.show();
}

function copiarContenido(contenido) {
    navigator.clipboard.writeText(contenido).then(() => {
        const toastElement = document.getElementById('copyToast');
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }).catch(err => {
        console.error('Error al copiar el contenido:', err);
    });
}