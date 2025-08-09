<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteTarifaEspecial extends Model
{
    use HasFactory;

    protected $table = 'cliente_tarifas_especiales';

    protected $fillable = [
        'cliente_id', 'tarifa_destino_id', 'rate_personalizado', 'activo'
    ];

    protected $casts = [
        'rate_personalizado' => 'decimal:4',
        'activo' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tarifaDestino()
    {
        return $this->belongsTo(TarifaDestino::class, 'tarifa_destino_id');
    }
}
