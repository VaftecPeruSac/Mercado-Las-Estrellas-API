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
            $table->id('id_deuda');
            $table->unsignedBigInteger('id_socio');
            $table->unsignedBigInteger('id_puesto');
            $table->decimal('total_deuda', 10, 2);
            $table->dateTime('fecha_registro')->useCurrent();

            // Relaciones
            $table->foreign('id_socio')->references('id_socio')->on('socios');
            $table->foreign('id_puesto')->references('id_puesto')->on('puestos');
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
