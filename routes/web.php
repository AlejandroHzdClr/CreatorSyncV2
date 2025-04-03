<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PerfilController;


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

    Route::get('/perfil/{id}', [PerfilController::class, 'show'])->name('perfil.show');
    Route::post('/perfil/{id}/follow', [PerfilController::class, 'follow'])->name('perfil.follow');
});

require __DIR__.'/auth.php';