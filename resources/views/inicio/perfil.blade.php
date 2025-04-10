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

<div class="body">
    <div id="foto_perfil">
        <img src="{{ $usuario->avatar ? asset($usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}" 
            alt="{{ $usuario->nombre }}" 
            class="foto">
        
        <!-- Mostrar el botón de edición solo si el perfil pertenece al usuario autenticado -->
        @if(Auth::id() === $usuario->id)
            <div id="edicion">
                <img src="{{ asset('images/editar.png') }}" alt="Editar" class="editar">
                <h2>Editar tu Perfil</h2>
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
                @if($usuario->perfil && $usuario->perfil->redes_sociales)
                    @php
                        $redes = json_decode($usuario->perfil->redes_sociales, true);
                    @endphp
                    @foreach($redes as $nombre => $url)
                        <a href="{{ $url }}" target="_blank">{{ ucfirst($nombre) }}</a><br>
                    @endforeach
                @else
                    <p>No hay redes sociales disponibles.</p>
                @endif
            </div>
        </div>

        <div id="biografia">
            <h1>Biografía</h1>
            <p id="contenido_biografia">{{ $usuario->descripcion ?? 'No hay biografía disponible.' }}</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Manejar el evento de clic en el botón de seguir o dejar de seguir
        $('.seguir-button').on('click', function () {
            const button = $(this);
            const usuarioId = button.data('usuario-id');
            const isFollowing = button.hasClass('btn-danger'); // Si tiene la clase "btn-danger", está siguiendo

            // Determinar la URL para seguir o dejar de seguir
            const url = isFollowing ? `/dejar-de-seguir/${usuarioId}` : `/seguir/${usuarioId}`;

            // Enviar la solicitud AJAX
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Actualizar el botón y el contador de seguidores
                    if (isFollowing) {
                        button.removeClass('btn-danger').addClass('btn-primary').text('Seguir');
                    } else {
                        button.removeClass('btn-primary').addClass('btn-danger').text('Dejar de seguir');
                    }
                    $('#seguidores-count').text(response.seguidores_count);
                },
                error: function (xhr) {
                    console.error('Error al seguir o dejar de seguir:', xhr.responseText);
                }
            });
        });
    });
</script>

@include('layouts.footer')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
