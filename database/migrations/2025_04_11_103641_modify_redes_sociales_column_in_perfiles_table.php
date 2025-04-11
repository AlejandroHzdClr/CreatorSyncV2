<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->json('redes_sociales')->nullable()->change(); // Cambiar el tipo a JSON
        });
    }

    public function down()
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->text('redes_sociales')->nullable()->change(); // Revertir a texto si es necesario
        });
    }
};
