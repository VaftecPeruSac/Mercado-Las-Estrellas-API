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
        Schema::create('deudas', function (Blueprint $table) {
            $table->id('id_deuda');;
            $table->unsignedBigInteger('id_socio');
            $table->integer('total_deuda');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socios');
            //$table->timestamps();
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
