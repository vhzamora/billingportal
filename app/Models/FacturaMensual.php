<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaMensual extends Model
{
    use HasFactory;

    protected $table = 'facturas_mensuales';

    protected $fillable = [
        'cliente_id', 'año', 'mes', 'total_llamadas', 'total_minutos', 'importe_total', 'generada_at'
    ];

    protected $casts = [
        'año' => 'integer',
        'mes' => 'integer',
        'total_llamadas' => 'integer',
        'total_minutos' => 'integer',
        'importe_total' => 'decimal:2',
        'generada_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
