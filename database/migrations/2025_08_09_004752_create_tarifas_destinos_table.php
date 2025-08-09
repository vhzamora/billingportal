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
        Schema::create('tarifas_destinos', function (Blueprint $table) {
            $table->id();
            $table->string('destination'); // México Mobile, USA Fixed, etc.
            $table->string('codes'); // Código numérico (52044, 521, 52, etc.)
            $table->decimal('cost', 8, 4)->default(0); // Costo interno (no visible al cliente)
            $table->decimal('rate', 8, 4); // Tarifa en pesos mexicanos por minuto
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices para optimizar búsquedas
            $table->index('codes');
            $table->index(['activo', 'codes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas_destinos');
    }
};
