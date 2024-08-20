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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id('id_cuota');
            $table->unsignedBigInteger('id_puesto');//nueva columna
            $table->unsignedBigInteger('id_servicio');
            $table->string('importe');
            $table->dateTime('fecha_vencimiento');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_puesto')
            ->references('id_puesto')
            ->on('puestos');
            $table->foreign('id_servicio')
            ->references('id_servicio')
            ->on('servicios');
            
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
