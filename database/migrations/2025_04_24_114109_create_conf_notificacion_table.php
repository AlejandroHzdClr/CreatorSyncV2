<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfNotificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conf_notificacion', function (Blueprint $table) {
            $table->id(); // ID de la configuración
            $table->unsignedBigInteger('user_id'); // Relación con la tabla de usuarios
            $table->boolean('likes')->default(true); // Notificaciones de likes
            $table->boolean('seguidores')->default(true); // Notificaciones de seguidores
            $table->boolean('comentarios')->default(true); // Notificaciones de comentarios
            $table->timestamps(); // Timestamps para created_at y updated_at

            // Clave foránea para asegurar la relación con la tabla de usuarios
            $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conf_notificacion');
    }
}