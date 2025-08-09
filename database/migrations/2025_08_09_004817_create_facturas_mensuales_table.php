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
        Schema::create('facturas_mensuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->integer('año');
            $table->integer('mes');
            $table->integer('total_llamadas');
            $table->integer('total_minutos');
            $table->decimal('importe_total', 10, 2);
            $table->datetime('generada_at');
            $table->timestamps();
            
            // Un cliente solo puede tener una factura por mes
            $table->unique(['cliente_id', 'año', 'mes']);
            $table->index(['año', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_mensuales');
    }
};
