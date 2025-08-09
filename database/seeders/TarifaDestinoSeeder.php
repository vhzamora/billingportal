<?php

namespace Database\Seeders;

use App\Models\TarifaDestino;
use Illuminate\Database\Seeder;

class TarifaDestinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // México - Tarifas específicas
        TarifaDestino::create([
            'destination' => 'México Fijo',
            'codes' => '52',
            'cost' => 0.50,
            'rate' => 1.20,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'México Móvil',
            'codes' => '521',
            'cost' => 0.80,
            'rate' => 1.80,
            'activo' => true,
        ]);
        
        // Ciudad de México más específico
        TarifaDestino::create([
            'destination' => 'México CDMX Fijo',
            'codes' => '5255',
            'cost' => 0.40,
            'rate' => 1.00,
            'activo' => true,
        ]);
        
        // Guadalajara específico
        TarifaDestino::create([
            'destination' => 'México Guadalajara Fijo',
            'codes' => '5233',
            'cost' => 0.45,
            'rate' => 1.10,
            'activo' => true,
        ]);
        
        // Estados Unidos
        TarifaDestino::create([
            'destination' => 'USA Fijo',
            'codes' => '1',
            'cost' => 0.30,
            'rate' => 0.80,
            'activo' => true,
        ]);
        
        // Canadá
        TarifaDestino::create([
            'destination' => 'Canadá',
            'codes' => '1',
            'cost' => 0.35,
            'rate' => 0.90,
            'activo' => true,
        ]);
        
        // España
        TarifaDestino::create([
            'destination' => 'España Fijo',
            'codes' => '349',
            'cost' => 1.20,
            'rate' => 2.50,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'España Móvil',
            'codes' => '346',
            'cost' => 1.50,
            'rate' => 3.00,
            'activo' => true,
        ]);
        
        // Reino Unido
        TarifaDestino::create([
            'destination' => 'Reino Unido Fijo',
            'codes' => '442',
            'cost' => 1.10,
            'rate' => 2.30,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'Reino Unido Móvil',
            'codes' => '447',
            'cost' => 1.40,
            'rate' => 2.80,
            'activo' => true,
        ]);
        
        // Francia
        TarifaDestino::create([
            'destination' => 'Francia Fijo',
            'codes' => '331',
            'cost' => 1.00,
            'rate' => 2.20,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'Francia Móvil',
            'codes' => '336',
            'cost' => 1.30,
            'rate' => 2.70,
            'activo' => true,
        ]);
        
        // Alemania
        TarifaDestino::create([
            'destination' => 'Alemania Fijo',
            'codes' => '4930',
            'cost' => 1.05,
            'rate' => 2.25,
            'activo' => true,
        ]);
        
        // Argentina
        TarifaDestino::create([
            'destination' => 'Argentina Fijo',
            'codes' => '5411',
            'cost' => 0.90,
            'rate' => 2.00,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'Argentina Móvil',
            'codes' => '549',
            'cost' => 1.20,
            'rate' => 2.60,
            'activo' => true,
        ]);
        
        // Colombia
        TarifaDestino::create([
            'destination' => 'Colombia Fijo',
            'codes' => '571',
            'cost' => 0.70,
            'rate' => 1.60,
            'activo' => true,
        ]);
        
        TarifaDestino::create([
            'destination' => 'Colombia Móvil',
            'codes' => '573',
            'cost' => 0.95,
            'rate' => 2.10,
            'activo' => true,
        ]);
        
        // ROW (Rest of World) - Tarifa por defecto
        TarifaDestino::create([
            'destination' => 'ROW - Resto del Mundo',
            'codes' => '00',
            'cost' => 2.00,
            'rate' => 4.50,
            'activo' => true,
        ]);
    }
}
