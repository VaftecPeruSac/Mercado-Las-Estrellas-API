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
        Schema::create('socios', function (Blueprint $table) {
            $table->id('id_socio');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombres', 150);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);
            $table->string('dni', 8);
            $table->string('correo', 150);
            $table->string('telefono', 9);
            $table->string('direccion', 150);
            $table->string('sexo', 10);
            $table->dateTime('fecha_registro')->useCurrent();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
