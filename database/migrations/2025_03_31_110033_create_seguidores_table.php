<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguidoresTable extends Migration
{
    public function up()
    {
        Schema::create('seguidores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('seguido_id');
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('seguido_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unique(['usuario_id', 'seguido_id']); // Evitar seguir a la misma persona varias veces
        });
    }

    public function down()
    {
        Schema::dropIfExists('seguidores');
    }
}
