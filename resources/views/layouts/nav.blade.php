<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreatorSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Barra de navegación que ocupa todo el ancho -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-5 py-3 border-bottom shadow-sm w-100">
        <!-- Logo -->
        <img src="{{ asset('images/CreatorsSyncLogo.png') }}" alt="CreatorsSyncLogo" class="logo" onclick="window.location.href = 'inicio'">

        <!-- Iconos de la derecha -->
        <div class="d-flex align-items-center gap-3 ms-auto">
            <img src="{{ asset('images/Campana.png') }}" alt="Notificaciones" class="campana cursor-pointer">
            <img src="{{ asset('images/PerfilPredeterminado.jpg') }}" alt="Perfil" class="foto_perfil cursor-pointer" onclick="window.location.href = 'perfil'">
        </div>
    </nav>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
