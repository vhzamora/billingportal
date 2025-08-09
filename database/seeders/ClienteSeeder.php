<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ClienteClid;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente 1: Empresa ABC
        $cliente1 = Cliente::create([
            'nombre' => 'Empresa ABC S.A. de C.V.',
            'email' => 'contacto@empresaabc.com',
            'telefono' => '555-123-4567',
            'razon_social' => 'Empresa ABC S.A. de C.V.',
            'rfc' => 'EAB123456789',
            'direccion' => 'Av. Reforma 123, Col. Centro, Ciudad de México',
            'activo' => true,
        ]);
        
        // CLIDs para Cliente 1
        ClienteClid::create(['cliente_id' => $cliente1->id, 'clid' => '"ABC Company" <1001>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente1->id, 'clid' => '"ABC Ventas" <1002>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente1->id, 'clid' => '"ABC Soporte" <1003>', 'activo' => true]);
        
        // Cliente 2: Corporativo XYZ
        $cliente2 = Cliente::create([
            'nombre' => 'Corporativo XYZ',
            'email' => 'admin@xyz.com.mx',
            'telefono' => '555-987-6543',
            'razon_social' => 'Corporativo XYZ S.C.',
            'rfc' => 'CXY987654321',
            'direccion' => 'Blvd. Tecnológico 456, Col. Moderna, Guadalajara, Jalisco',
            'activo' => true,
        ]);
        
        // CLIDs para Cliente 2
        ClienteClid::create(['cliente_id' => $cliente2->id, 'clid' => '"XYZ Corp" <2001>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente2->id, 'clid' => '"XYZ Marketing" <2002>', 'activo' => true]);
        
        // Cliente 3: Servicios DEF
        $cliente3 = Cliente::create([
            'nombre' => 'Servicios DEF',
            'email' => 'info@serviciosdef.mx',
            'telefono' => '555-555-1234',
            'razon_social' => 'Servicios Integrales DEF S.A.',
            'rfc' => 'SID555123456',
            'direccion' => 'Calle Principal 789, Col. Industrial, Monterrey, Nuevo León',
            'activo' => true,
        ]);
        
        // CLIDs para Cliente 3
        ClienteClid::create(['cliente_id' => $cliente3->id, 'clid' => '"DEF Services" <3001>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente3->id, 'clid' => '"DEF Call Center" <3002>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente3->id, 'clid' => '"DEF Admin" <3003>', 'activo' => true]);
        ClienteClid::create(['cliente_id' => $cliente3->id, 'clid' => '"DEF Ventas" <3004>', 'activo' => true]);
    }
}
