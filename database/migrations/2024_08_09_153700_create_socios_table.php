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
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('correo');
            $table->string('telefono');
            $table->string('estado');
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_gironegocio');  
            $table->foreign('id_gironegocio')
            ->references('id')
            ->on('giro_negocios');
            $table->foreign('id_persona')
			->references('id')
            ->on('personas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
