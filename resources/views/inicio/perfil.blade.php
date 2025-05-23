<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $usuario->nombre}}</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/post.js') }}"></script>
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
        <div id="edicion" class="d-flex align-items-center mt-3" 
            data-bs-toggle="modal" 
            data-bs-target="#editarPerfilModal"
            style="cursor: pointer;">
            <img src="{{ asset('images/editar.png') }}" 
                alt="Editar" 
                class="editar me-2"
                style="cursor: pointer; width: 30px; height: 30px;">
            <span style="font-size: 1.2rem; font-weight: bold; cursor: pointer;">
                Editar Perfil
            </span>
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

        <!-- Todos los Posts -->
        <div id="publicaciones" class="container" style="display: flex; flex-direction: column; align-items: center; margin-bottom:7%">
            <h1 class="text-center mb-4">Publicaciones</h1>
            @foreach($publicaciones as $post)
                <x-post :post="$post" :width="100" />
            @endforeach
        </div>
    </div>
</div>

<!-- Modal para editar perfil -->
<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <form id="editarPerfilForm" method="POST" action="{{ route('perfil.update', $usuario->id) }}">
                 @csrf
                 @method('PUT')
                 <div class="modal-header">
                     <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <!-- Descripción -->
                     <div class="mb-3">
                         <label for="descripcion" class="form-label">Descripción</label>
                         <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ $usuario->descripcion }}</textarea>
                     </div>
                     <!-- Redes Sociales -->
                     <div class="mb-3">
                         <label for="redes_sociales" class="form-label">Redes Sociales</label>
                         <div id="redesSocialesInputs">
                             @if($usuario->perfil && !empty($usuario->perfil->redes_sociales))
                                 @foreach($usuario->perfil->redes_sociales as $index => $red)
                                     @if(is_array($red) && isset($red['nombre'], $red['url']))
                                         <div class="input-group mb-2">
                                             <input type="text" class="form-control" name="redes[{{ $index }}][nombre]" value="{{ $red['nombre'] }}" placeholder="Nombre de la red social">
                                             <input type="url" class="form-control" name="redes[{{ $index }}][url]" value="{{ $red['url'] }}" placeholder="URL de la red social">
                                             <button type="button" class="btn btn-danger eliminar-red" onclick="eliminarRed(this)">Eliminar</button>
                                         </div>
                                     @endif
                                 @endforeach
                             @endif
                         </div>
                         <button type="button" class="btn btn-primary mt-2" id="agregarRed">Agregar Red Social</button> 
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                     <button type="submit" class="btn btn-success">Guardar Cambios</button>
                 </div>
             </form>
         </div>
     </div>
 </div>

<script>

    $(document).ready(function () {

        // Agregar nueva red social
        let redIndex = {{ isset($usuario->perfil->redes_sociales) ? count($usuario->perfil->redes_sociales) : 0 }};
 
         // Agregar un nuevo campo para redes sociales
        $('#agregarRed').on('click', function () {
            const nuevaRed = `
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="redes[${redIndex}][nombre]" placeholder="Nombre de la red social">
                    <input type="url" class="form-control" name="redes[${redIndex}][url]" placeholder="URL de la red social">
                    <button type="button" class="btn btn-danger eliminar-red" onclick="eliminarRed(this)">Eliminar</button>
                </div>`;
            $('#redesSocialesInputs').append(nuevaRed);
            redIndex++;
        });
 
        // Eliminar un campo de red social
        window.eliminarRed = function (button) {
            $(button).closest('.input-group').remove();
        };

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
    });
</script>

@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>