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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_socio');  
            $table->unsignedBigInteger('id_documento');
            $table->integer('numero_pago');
            $table->integer('serie');
            $table->integer('total_pago');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socios');
            $table->foreign('id_documento')
            ->references('id_documento')
            ->on('documentos');
            //$table->timestamps();
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
