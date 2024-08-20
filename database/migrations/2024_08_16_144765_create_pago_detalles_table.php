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
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->id('id_detallepago');
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('id_cuota');
            $table->unsignedBigInteger('id_puesto');
            $table->integer('importe');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_pago')
            ->references('id_pago')
            ->on('pagos');
            $table->foreign('id_cuota')
            ->references('id_cuota')
            ->on('cuotas');
            $table->foreign('id_puesto')
            ->references('id_puesto')
            ->on('puestos');
            //$table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_detalles');
    }
};
