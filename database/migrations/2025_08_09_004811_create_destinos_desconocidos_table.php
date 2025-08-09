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
        Schema::create('destinos_desconocidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_destino');
            $table->integer('cantidad_llamadas')->default(1);
            $table->boolean('notificado')->default(false);
            $table->boolean('procesado')->default(false); // Si ya se agregó tarifa
            $table->timestamps();
            
            $table->unique('numero_destino');
            $table->index('notificado');
            $table->index('procesado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinos_desconocidos');
    }
};
