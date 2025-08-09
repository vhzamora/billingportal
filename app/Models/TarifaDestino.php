<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaDestino extends Model
{
    use HasFactory;
    
    protected $table = 'tarifas_destinos';
    
    protected $fillable = [
        'destination',
        'codes',
        'cost',
        'rate',
        'activo'
    ];
    
    protected $casts = [
        'cost' => 'decimal:4',
        'rate' => 'decimal:4',
        'activo' => 'boolean',
    ];
    
    // Relaciones
    public function tarifasEspeciales()
    {
        return $this->hasMany(ClienteTarifaEspecial::class);
    }
    
    // Métodos estáticos para búsqueda de tarifas
    public static function buscarTarifaPorNumero($numeroDestino, $clienteId = null)
    {
        // Intentar buscar tarifa especial del cliente primero
        if ($clienteId) {
            $tarifaEspecial = ClienteTarifaEspecial::where('cliente_id', $clienteId)
                ->whereHas('tarifaDestino', function($query) use ($numeroDestino) {
                    $query->where('activo', true);
                    for ($i = 10; $i >= 2; $i--) {
                        $prefix = substr($numeroDestino, 0, $i);
                        $query->orWhere('codes', $prefix);
                    }
                })
                ->with('tarifaDestino')
                ->first();
                
            if ($tarifaEspecial) {
                return [
                    'tarifa' => $tarifaEspecial->tarifaDestino,
                    'rate' => $tarifaEspecial->rate_personalizado,
                    'codigo_matched' => $tarifaEspecial->tarifaDestino->codes,
                ];
            }
        }
        
        // Buscar tarifa general (de 10 dígitos hacia abajo)
        for ($i = 10; $i >= 2; $i--) {
            $prefix = substr($numeroDestino, 0, $i);
            $tarifa = self::where('codes', $prefix)
                ->where('activo', true)
                ->first();
                
            if ($tarifa) {
                return [
                    'tarifa' => $tarifa,
                    'rate' => $tarifa->rate,
                    'codigo_matched' => $prefix,
                ];
            }
        }
        
        return null; // Destino desconocido
    }
    
    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
