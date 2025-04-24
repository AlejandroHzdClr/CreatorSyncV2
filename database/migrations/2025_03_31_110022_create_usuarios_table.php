<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('rol', ['admin', 'moderador', 'creador', 'usuario'])->default('usuario');
            $table->timestamps();
            $table->string('avatar')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
