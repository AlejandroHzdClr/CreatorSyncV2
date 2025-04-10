@extends('layouts.app')

@include('layouts.nav')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    #loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1050;
    text-align: center;
}

#loading-overlay .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
<!-- Overlay de carga -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 1050; text-align: center;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <p style="margin-top: 10px;">Procesando...</p>
    </div>
</div>

<div class="container">
    <h1>Notificaciones</h1>

    <!-- Botones para acciones masivas -->
    <div class="mb-3 d-flex gap-2">
        <form action="{{ route('notificaciones.marcarTodasLeidas') }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Marcar todas como leídas</button>
        </form>
        <form action="{{ route('notificaciones.eliminarTodas') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar todas las notificaciones</button>
        </form>
    </div>

    <!-- Lista de notificaciones -->
    <ul class="list-group">
        @forelse($notificaciones as $notificacion)
            <li class="list-group-item {{ $notificacion->leida ? '' : 'list-group-item-info' }}" id="notificacion-{{ $notificacion->id }}">
                {{ $notificacion->mensaje }}
                <small class="text-muted d-block">{{ $notificacion->created_at->diffForHumans() }}</small>
                <div class="d-flex gap-2">
                    @if(!$notificacion->leida)
                        <button class="btn btn-sm btn-secondary marcar-leida" data-id="{{ $notificacion->id }}">Marcar como leída</button>
                    @endif
                    <button class="btn btn-sm btn-danger eliminar-notificacion" data-id="{{ $notificacion->id }}">Eliminar</button>
                </div>
            </li>
        @empty
            <li class="list-group-item">No tienes notificaciones.</li>
        @endforelse
    </ul>
</div>
<script>
    $(document).ready(function () {
        // Mostrar el overlay de carga
        function mostrarCargando() {
            $('#loading-overlay').fadeIn();
        }

        // Ocultar el overlay de carga
        function ocultarCargando() {
            $('#loading-overlay').fadeOut();
        }

        // Marcar notificación como leída
        $('.marcar-leida').on('click', function () {
            const notificacionId = $(this).data('id');
            const notificacionItem = $(`#notificacion-${notificacionId}`);

            mostrarCargando();

            $.ajax({
                url: `/notificaciones/${notificacionId}/marcar-leida`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    notificacionItem.removeClass('list-group-item-info');
                    notificacionItem.find('.marcar-leida').remove(); // Eliminar el botón de "Marcar como leída"
                },
                error: function (xhr) {
                    console.error('Error al marcar la notificación como leída:', xhr.responseText);
                },
                complete: function () {
                    ocultarCargando();
                }
            });
        });

        // Eliminar notificación
        $('.eliminar-notificacion').on('click', function () {
            const notificacionId = $(this).data('id');
            const notificacionItem = $(`#notificacion-${notificacionId}`);

            mostrarCargando();

            $.ajax({
                url: `/notificaciones/${notificacionId}/eliminar`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    notificacionItem.remove(); // Eliminar la notificación de la lista
                },
                error: function (xhr) {
                    console.error('Error al eliminar la notificación:', xhr.responseText);
                },
                complete: function () {
                    ocultarCargando();
                }
            });
        });
    });
</script>
@include('layouts.footer')
@endsection