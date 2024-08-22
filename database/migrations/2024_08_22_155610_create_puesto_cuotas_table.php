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
        Schema::create('puesto_cuotas', function (Blueprint $table) {
            $table->id('id_puesto_cuota');
            $table->unsignedBigInteger('id_puesto');
            $table->unsignedBigInteger('id_cuota');
            $table->string('estado');
            $table->foreign('id_puesto')
            ->references('id_puesto')
            ->on('puestos');
            $table->foreign('id_cuota')
            ->references('id_cuota')
            ->on('cuotas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puesto_cuotas');
    }
};
