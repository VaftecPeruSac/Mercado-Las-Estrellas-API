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
        Schema::create('deuda', function (Blueprint $table) {
            $table->id('id_deuda');
            $table->unsignedBigInteger('id_cuota');
            $table->unsignedBigInteger('id_puesto');
            $table->unsignedBigInteger('id_socio');
            $table->unsignedBigInteger('id_servicio');
            $table->integer('importe');
            $table->dateTime('fecha_registro');
            $table->integer('id_usuarioregistro');
            $table->foreign('id_cuota')
            ->references('id_cuota')
            ->on('cuota');
            $table->foreign('id_puesto')
            ->references('id_puesto')
            ->on('puesto');
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socio');
            $table->foreign('id_servicio')
            ->references('id_servicio')
            ->on('servicio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deudas');
    }
};
