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
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_socio');  
            $table->unsignedBigInteger('id_documento');
            $table->integer('total');
            $table->dateTime('fecha_registro');
            $table->integer('id_usuarioregistro');
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socio');
            $table->foreign('id_documento')
            ->references('id_documento')
            ->on('documento');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
