<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    protected $table = 'clientes';
    
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'razon_social',
        'rfc',
        'direccion',
        'activo'
    ];
    
    protected $casts = [
        'activo' => 'boolean',
    ];
    
    // Relaciones
    public function clids()
    {
        return $this->hasMany(ClienteClid::class);
    }
    
    public function clidsActivos()
    {
        return $this->hasMany(ClienteClid::class)->where('activo', true);
    }
    
    public function llamadasFacturadas()
    {
        return $this->hasMany(LlamadaFacturada::class);
    }
    
    public function tarifasEspeciales()
    {
        return $this->hasMany(ClienteTarifaEspecial::class);
    }
    
    public function facturasMensuales()
    {
        return $this->hasMany(FacturaMensual::class);
    }
    
    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
