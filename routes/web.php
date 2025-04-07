<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ConfiguracionController;


Route::get('/dashboard', function () {
    return view('inicio.index');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::post('/perfil/{id}/follow', [PerfilController::class, 'follow'])->name('perfil.follow');

    // Rutas relacionadas con la configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/perfil', [ConfiguracionController::class, 'updatePerfil'])->name('configuracion.updatePerfil');
    Route::post('/configuracion/seguridad', [ConfiguracionController::class, 'updateSeguridad'])->name('configuracion.updateSeguridad');
    Route::post('/configuracion/notificaciones', [ConfiguracionController::class, 'updateNotificaciones'])->name('configuracion.updateNotificaciones');
    
});

require __DIR__.'/auth.php';