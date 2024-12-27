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
        Schema::create('puestos', function (Blueprint $table) {
            $table->id('id_puesto');
            $table->unsignedBigInteger('id_socio')->nullable();
            $table->unsignedBigInteger('id_block');
            $table->unsignedBigInteger('id_gironegocio');
            $table->string('numero_puesto', 30);
            $table->integer('area');
            $table->unsignedBigInteger('id_inquilino')->nullable();
            $table->integer('estado')->default(1); // 1: Disponible, 2: Ocupado
            $table->boolean('activo')->default(1); // 0: Inactivo, 1: Activo
            $table->dateTime('fecha_registro')->useCurrent();

            // Relaciones
            $table->foreign('id_socio')->references('id_socio')->on('socios');
            $table->foreign('id_block')->references('id_block')->on('blocks');
            $table->foreign('id_gironegocio')->references('id_gironegocio')->on('giro_negocios');
            $table->foreign('id_inquilino')->references('id_inquilino')->on('inquilinos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puestos');
    }
};
