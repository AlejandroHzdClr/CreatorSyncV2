@extends('layouts.app')

@section('content')
@include('layouts.nav')
<div class="container">
    <h1>Configuración</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <a href="#perfil" class="list-group-item list-group-item-action active" data-bs-toggle="tab">Perfil</a>
                <a href="#seguridad" class="list-group-item list-group-item-action" data-bs-toggle="tab">Seguridad</a>
                <a href="#notificaciones" class="list-group-item list-group-item-action" data-bs-toggle="tab">Notificaciones</a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="tab-content">
                <!-- Formulario de Perfil -->
                <div class="tab-pane fade show active" id="perfil">
                    <h2>Perfil</h2>
                    <form action="{{ route('configuracion.updatePerfil') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ Auth::user()->nombre }}" placeholder="Ingresa tu nombre">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}" placeholder="Ingresa tu email">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto de perfil</label>
                            <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                            <img id="preview" src="{{ Auth::user()->avatar }}" alt="Vista previa" class="img-thumbnail mt-3" style="max-width: 200px; display: block;">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

                <!-- Formulario de Seguridad -->
                <div class="tab-pane fade" id="seguridad">
                    <h2>Seguridad</h2>
                    <form action="{{ route('configuracion.updateSeguridad') }}" method="POST">
                        @csrf
                        <!-- Campo oculto para el email/usuario (requerido para accesibilidad) -->
                        <input type="email" name="email" value="{{ Auth::user()->email }}" autocomplete="username" hidden>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña actual</label>
                            <input type="password" id="password" name="password" class="form-control" 
                                placeholder="Ingresa tu contraseña actual" autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nueva contraseña</label>
                            <input type="password" id="newPassword" name="newPassword" class="form-control" 
                                placeholder="Ingresa tu nueva contraseña" autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword_confirmation" class="form-label">Confirmar contraseña</label>
                            <input type="password" id="newPassword_confirmation" name="newPassword_confirmation" 
                                class="form-control" placeholder="Confirma tu nueva contraseña" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

                <!-- Formulario de Notificaciones -->
                <div class="tab-pane fade" id="notificaciones">
                    <h2>Notificaciones</h2>
                    <form action="{{ route('configuracion.updateNotificaciones') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notiLikes" name="likes" {{ Auth::user()->likes ? 'checked' : '' }}>
                            <label class="form-check-label" for="notiLikes">Likes</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notiSeguidores" name="seguidores" {{ Auth::user()->seguidores ? 'checked' : '' }}>
                            <label class="form-check-label" for="notiSeguidores">Seguidores</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notiComentarios" name="comentarios" {{ Auth::user()->comentarios ? 'checked' : '' }}>
                            <label class="form-check-label" for="notiComentarios">Comentarios</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Vista previa de la imagen
    const fotoInput = document.getElementById("foto");
    const preview = document.getElementById("preview");
    
    if (fotoInput && preview) {
        fotoInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                
                reader.readAsDataURL(file);
            } else {
                // Restablecer a la imagen por defecto si no se selecciona una imagen válida
                preview.src = "{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}";
            }
        });
    } else {
        console.error("Elementos necesarios no encontrados");
    }
});
</script>
@include('layouts.footer')
@endsection