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
            $table->string('correo');
            $table->string('telefono');
            $table->string('estado');
            $table->integer('id_usuarioregistro');
            $table->dateTime('fecha_registro');
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_gironegocio');  
            $table->foreign('id_gironegocio')
            ->references('id_gironegocio')
            ->on('giro_negocios');
            $table->foreign('id_persona')
			->references('id_persona')
            ->on('personas');
            //$table->timestamps();
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
