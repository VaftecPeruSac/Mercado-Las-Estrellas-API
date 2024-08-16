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
        Schema::create('pago_detalle', function (Blueprint $table) {
            $table->id('id_pagodetalle');
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('id_deuda');
            $table->integer('importe');
            $table->dateTime('fecha_registro');
            $table->integer('id_usuarioregistro');
            $table->foreign('id_pago')
            ->references('id_pago')
            ->on('pago');
            $table->foreign('id_deuda')
            ->references('id_deuda')
            ->on('deuda');
            $table->timestamps();
           
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
