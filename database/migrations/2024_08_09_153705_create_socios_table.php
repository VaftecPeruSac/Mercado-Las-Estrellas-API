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
            $table->string('tipo_persona');
            $table->string('saldo');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_usuario')
            ->references('id_usuario')
            ->on('usuarios');
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
