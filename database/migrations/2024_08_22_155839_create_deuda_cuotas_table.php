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
        Schema::create('deuda_cuotas', function (Blueprint $table) {
            $table->id('id_deuda_cuota');
            $table->unsignedBigInteger('id_deuda');
            $table->unsignedBigInteger('id_cuota');
            $table->string('estado');
            $table->foreign('id_deuda')
            ->references('id_deuda')
            ->on('deudas');
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
        Schema::dropIfExists('deuda_cuotas');
    }
};
