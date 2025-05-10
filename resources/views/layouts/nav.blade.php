<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreatorSync</title>
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/post.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <!-- Barra de navegación -->
    <nav class="navbar navbar-light bg-white px-5 py-3 border-bottom shadow-sm w-100">
        <!-- Logo -->
        <a href="{{ route('inicio.index') }}">
            <img src="{{ asset('images/CreatorsSyncLogo.png') }}" alt="CreatorsSyncLogo" class="logo" >
        </a>
        <!-- Buscador -->
        <form action="{{ route('buscar') }}" method="GET" class="d-flex me-3">
            <input type="text" name="termino" class="form-control" placeholder="Buscar..." aria-label="Buscar" required>
            <button type="submit" class="btn btn-primary ms-2">Buscar</button>
        </form>
        <!-- Iconos de la derecha -->
        <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
            <a href="{{ route('notificaciones.index') }}">
                <img src="{{ asset('images/Campana.png') }}" alt="Notificaciones" class="campana cursor-pointer" onclick="">
            </a>
            @if($notificacionesNoLeidas > 0)
                <span class="notificacion-indicador"></span>
            @endif
        </div>

            <!-- Menú desplegable personalizado -->
            <div class="dropdown d-flex align-items-center">
                <!-- Avatar del usuario -->
                <img src="{{ Auth::user() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/PerfilPredeterminado.jpg') }}" 
                    alt="Perfil" 
                    class="foto_perfil" 
                    style="width: 75px; border-radius: 50%;">

                <!-- Indicador de rol de admin -->
                @if(Auth::user() && Auth::user()->rol === 'admin')
                    <span class="badge bg-danger ms-2">Admin</span>
                @endif

                <div class="dropdown-menu">
                    <a href="{{ route('perfil.show', Auth::user()->id) }}">Perfil</a>
                    <a href="{{ route('configuracion.index', Auth::user()->id) }}">Configuración</a>

                    <!-- Opción para el panel de administración -->
                    @if(Auth::user() && Auth::user()->rol === 'admin')
                        <a href="{{ route('admin.index') }}">Panel de Administración</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><strong>Cerrar sesión</strong></button>
                    </form>
                </div>
            </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>