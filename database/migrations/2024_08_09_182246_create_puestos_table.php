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
            $table->unsignedBigInteger('id_socio');
            $table->unsignedBigInteger('id_gironegocio');
            $table->unsignedBigInteger('id_block');
            $table->string('numero_puesto');
            $table->string('area');
            $table->unsignedBigInteger('id_inquilino');
            $table->string('estado');
            $table->dateTime('fecha_registro');
            //relaciones
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socios');
            $table->foreign('id_gironegocio')
            ->references('id_gironegocio')
            ->on('giro_negocios');
            $table->foreign('id_block')
            ->references('id_block')
            ->on('blocks');
            $table->foreign('id_inquilino')
            ->references('id_inquilino')
            ->on('inquilinos');
            //$table->timestamps();
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
