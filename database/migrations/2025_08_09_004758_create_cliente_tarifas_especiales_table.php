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
        Schema::create('cliente_tarifas_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('tarifa_destino_id')->constrained('tarifas_destinos')->onDelete('cascade');
            $table->decimal('rate_personalizado', 8, 4); // Tarifa especial para este cliente
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Un cliente solo puede tener una tarifa especial por destino
            $table->unique(['cliente_id', 'tarifa_destino_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_tarifas_especiales');
    }
};
