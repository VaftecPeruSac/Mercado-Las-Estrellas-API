<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_persona');
            $table->string('nombre_usuario');
            $table->string('contrasenia');
            $table->string('rol')->nullable();
            $table->string('estado');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_persona')
            ->references('id_persona')
            ->on('personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
