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
        Schema::create('llamadas_facturadas', function (Blueprint $table) {
            $table->id();
            $table->string('cdr_uniqueid')->unique(); // uniqueid del CDR
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('clid');
            
            // Datos de la llamada
            $table->string('numero_origen');
            $table->string('numero_destino');
            $table->datetime('fecha_llamada');
            $table->integer('duracion_segundos'); // billsec del CDR
            $table->integer('minutos_facturados'); // Redondeado hacia arriba
            
            // Información de tarificación
            $table->string('destination_encontrado')->nullable();
            $table->string('codigo_matched')->nullable();
            $table->decimal('tarifa_aplicada', 8, 4);
            $table->decimal('costo_cliente', 8, 4); // Total a cobrar al cliente
            $table->boolean('es_destino_desconocido')->default(false);
            
            // Control de reprocesamiento
            $table->boolean('reprocesado')->default(false);
            $table->datetime('reprocesado_at')->nullable();
            $table->datetime('procesado_at');
            
            $table->timestamps();
            
            // Índices para consultas rápidas
            $table->index('cliente_id');
            $table->index('fecha_llamada');
            $table->index('clid');
            $table->index('es_destino_desconocido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamadas_facturadas');
    }
};
