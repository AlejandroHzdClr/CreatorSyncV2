$(document).ready(function () {
    $('#perfil').click(function () {
        modPerfil();
    });
    $('#seguridad').click(function () {
        modSeguridad();
    });
    $('#notificaciones').click(function () {
        modNotificaciones();
    });
});

var configuracion = document.getElementById("configuracion");

function modPerfil() {
    configuracion.innerHTML = `
        <div class="perfil-container">
            <h1>Perfil</h1>
            <form id="perfilForm" action="/configuracion/perfil" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" class="form-control mb-3">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu email" class="form-control mb-3">

                <label for="foto">Foto de perfil:</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-control mb-3">

                <img id="preview" src="" alt="Vista previa" style="display: none;">

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    `;

    // Vista previa de la imagen antes de enviarla
    document.getElementById("foto").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById("preview");
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });
}

function modSeguridad() {
    configuracion.innerHTML = `
        <div class="seguridad-container">
            <h1>Seguridad</h1>
            <form id="seguridadForm" action="/configuracion/seguridad" method="POST">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <label for="password">Contraseña actual:</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña actual" class="form-control mb-3">

                <label for="newPassword">Nueva contraseña:</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Ingresa tu nueva contraseña" class="form-control mb-3">

                <label for="newPassword_confirmation">Confirmar contraseña:</label>
                <input type="password" id="newPassword_confirmation" name="newPassword_confirmation" placeholder="Confirma tu nueva contraseña" class="form-control mb-3">

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    `;
}

function modNotificaciones() {
    configuracion.innerHTML = `
        <div class="notificaciones-container">
            <h1>Notificaciones</h1>
            <form id="notificacionesForm" action="/configuracion/notificaciones" method="POST">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notiLikes" name="likes">
                    <label class="form-check-label" for="notiLikes">Likes</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notiSeguidores" name="seguidores">
                    <label class="form-check-label" for="notiSeguidores">Seguidores</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notiComentarios" name="comentarios">
                    <label class="form-check-label" for="notiComentarios">Comentarios</label>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
            </form>
        </div>
    `;
}