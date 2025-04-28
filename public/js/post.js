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
    $(document).on('submit', '.comentario-form', function (event) {
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
                _token: $('meta[name="csrf-token"]').attr('content'),
                contenido: contenido
            },
            success: function (response) {
                comentariosList.append(`
                    <div class="comentario mt-2">
                        <strong>${response.usuario_nombre}:</strong> ${response.contenido}
                    </div>
                `);
                input.val('');
            },
            error: function (xhr) {
                console.error('Error al agregar el comentario:', xhr.responseText);
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