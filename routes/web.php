<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\SeguidorController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\NotificacionController;


Route::get('/dashboard', function () {
    return view('inicio.index');
})->middleware(['auth', 'verified'])->name('inicio.index');

Route::middleware('auth')->group(function () {
    Route::get('/', [PublicacionController::class, 'index'])->name('inicio.index');


    // Rutas relacionadas con el perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas relacionadas con publicaciones
    Route::get('/inicio', [PublicacionController::class, 'index'])->name('inicio.index');
    Route::post('/inicio', [PublicacionController::class, 'store'])->name('inicio.store');

    // Rutas relacionadas con el registro y login
    Route::get('/perfil/{id}', [PerfilController::class, 'show'])->name('perfil.show');
    Route::put('/perfil/{id}', [PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/{id}/follow', [PerfilController::class, 'follow'])->name('perfil.follow');

    // Rutas relacionadas con la configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/perfil', [ConfiguracionController::class, 'updatePerfil'])->name('configuracion.updatePerfil');
    Route::post('/configuracion/seguridad', [ConfiguracionController::class, 'updateSeguridad'])->name('configuracion.updateSeguridad');
    Route::post('/configuracion/notificaciones', [ConfiguracionController::class, 'updateNotificaciones'])->name('configuracion.updateNotificaciones');
    
    // Rutas relacionadas con seguidores
    Route::post('/seguir/{id}', [SeguidorController::class, 'seguir'])->name('seguir');
    Route::post('/dejar-de-seguir/{id}', [SeguidorController::class, 'dejarDeSeguir'])->name('dejarDeSeguir');

    // Rutas relacionadas con likes
    Route::post('/like/{id}', [LikeController::class, 'like'])->name('like');
    Route::post('/unlike/{id}', [LikeController::class, 'unlike'])->name('unlike');

    // Rutas relacionadas con comentarios
    Route::post('/comentarios/{id}', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::get('/comentarios/{id}', [ComentarioController::class, 'index'])->name('comentarios.index');

    // Rutas relacionadas con notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::patch('/notificaciones/{id}/marcar-leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcarLeida');
    Route::delete('/notificaciones/{id}/eliminar', [NotificacionController::class, 'eliminar'])->name('notificaciones.eliminar');
    Route::delete('/notificaciones', [NotificacionController::class, 'eliminarTodas'])->name('notificaciones.eliminarTodas');
    Route::patch('/notificaciones/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcarTodasLeidas');
});

require __DIR__.'/auth.php';