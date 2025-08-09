<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlamadaFacturada extends Model
{
    use HasFactory;

    protected $table = 'llamadas_facturadas';

    protected $fillable = [
        'cdr_uniqueid', 'cliente_id', 'clid', 'numero_origen', 'numero_destino',
        'fecha_llamada', 'duracion_segundos', 'minutos_facturados',
        'destination_encontrado', 'codigo_matched', 'tarifa_aplicada',
        'costo_cliente', 'es_destino_desconocido', 'reprocesado', 'reprocesado_at', 'procesado_at'
    ];

    protected $casts = [
        'fecha_llamada' => 'datetime',
        'duracion_segundos' => 'integer',
        'minutos_facturados' => 'integer',
        'tarifa_aplicada' => 'decimal:4',
        'costo_cliente' => 'decimal:4',
        'es_destino_desconocido' => 'boolean',
        'reprocesado' => 'boolean',
        'procesado_at' => 'datetime',
        'reprocesado_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
