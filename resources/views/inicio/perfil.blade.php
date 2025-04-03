<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - CreatorSync</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
</head>
<body>
@include('layouts.nav')

    <div class="body">
        <div id="foto_perfil">
            <img src="{{ $usuario->avatar ? asset($usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}" 
                alt="{{ $usuario->nombre }}" 
                class="foto">
            <div id="edicion">
                <img src="{{ asset('images/editar.png') }}" alt="Editar" class="editar">
                <h2>Editar tu Perfil</h2>
            </div>
        </div>
        <div id="contenido">
            <div id="datos">
                <div id="nombre">
                    <h1>{{ $usuario->nombre }}</h1>
                    <h2>Seguidores: {{ $usuario->seguidores_count }}</h2>
                </div>
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
</body>
</html>