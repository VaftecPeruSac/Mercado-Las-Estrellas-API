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
        Schema::create('cuota', function (Blueprint $table) {
            $table->id('id_cuota');
            $table->unsignedBigInteger('id_servicio');
            $table->string('precio');
            $table->string('id_usuarioregistro');
            $table->dateTime('fecha_registro');
            $table->foreign('id_servicio')
            ->references('id_servicio')
            ->on('servicio');
            $table->timestamps();
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
