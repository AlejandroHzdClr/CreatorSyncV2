<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreatorSync</title>
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Barra de navegación -->
    <nav class="navbar navbar-light bg-white px-5 py-3 border-bottom shadow-sm w-100">
        <!-- Logo -->
        <a href="{{ route('inicio.index') }}">
            <img src="{{ asset('images/CreatorsSyncLogo.png') }}" alt="CreatorsSyncLogo" class="logo" >
        </a>
        <!-- Iconos de la derecha -->
        <div class="d-flex align-items-center gap-3 ms-auto">
            <img src="{{ asset('images/Campana.png') }}" alt="Notificaciones" class="campana cursor-pointer">

            <!-- Menú desplegable personalizado -->
            <div class="dropdown">
                <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/PerfilPredeterminado.jpg') }}" alt="Perfil" class="foto_perfil">
                <div class="dropdown-menu">
                    <a href="{{ route('perfil.show', Auth::user()->id) }}">Perfil</a>
                    <a href="{{ route('perfil.show', Auth::user()->id) }}">Configuración</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                </div>
        </div>
    </nav>

</body>
</html>