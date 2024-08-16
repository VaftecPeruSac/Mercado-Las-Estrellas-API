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
        Schema::create('puesto', function (Blueprint $table) {
            $table->id('id_puesto');
            $table->string('nombre');
            $table->unsignedBigInteger('id_socio');
            $table->unsignedBigInteger('id_block');
            $table->string('area');
            $table->unsignedBigInteger('id_inquilino');
            $table->string('estado');
            $table->integer('id_usuarioregistro');
            $table->dateTime('fecha_registro');
            $table->foreign('id_socio')
            ->references('id_socio')
            ->on('socio');
            $table->foreign('id_block')
            ->references('id_block')
            ->on('block');
            $table->foreign('id_inquilino')
            ->references('id_persona')
            ->on('persona');
            $table->timestamps();
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
