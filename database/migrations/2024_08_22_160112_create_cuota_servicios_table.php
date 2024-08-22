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
        Schema::create('cuota_servicios', function (Blueprint $table) {
            $table->id('id_cuota_servicio');
            $table->unsignedBigInteger('id_cuota');
            $table->unsignedBigInteger('id_servicio');
            $table->foreign('id_cuota')
            ->references('id_cuota')
            ->on('cuotas');
            $table->foreign('id_servicio')
            ->references('id_servicio')
            ->on('servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuota_servicios');
    }
};
