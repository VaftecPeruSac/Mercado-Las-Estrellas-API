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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_socio');  
            $table->unsignedBigInteger('id_documento');
            $table->integer('numero_pago');
            $table->integer('serie');
            $table->decimal('total_pago', 10, 2);
            $table->dateTime('fecha_registro')->useCurrent();
            
            // Relaciones
            $table->foreign('id_socio')->references('id_socio')->on('socios');
            $table->foreign('id_documento')->references('id_documento')->on('documentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
