<?php

namespace Database\Seeders;

use App\Models\CdrSimulado;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CdrSimuladoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseDate = Carbon::now()->subDays(30);
        
        // Generar CDRs para el último mes
        for ($i = 0; $i < 100; $i++) {
            $callDate = $baseDate->copy()->addHours(rand(0, 24*30))->addMinutes(rand(0, 59));
            $duration = rand(30, 900); // 30 segundos a 15 minutos
            $billsec = $duration - rand(5, 20); // Restar tiempo de ring
            
            // CLIDs de prueba que corresponden a nuestros clientes
            $clids = [
                '"ABC Company" <1001>',
                '"ABC Ventas" <1002>', 
                '"ABC Soporte" <1003>',
                '"XYZ Corp" <2001>',
                '"XYZ Marketing" <2002>',
                '"DEF Services" <3001>',
                '"DEF Call Center" <3002>',
                '"DEF Admin" <3003>',
                '"DEF Ventas" <3004>'
            ];
            
            // Números de destino variados para probar tarifas
            $destinos = [
                '525512345678', // CDMX
                '523312345678', // Guadalajara  
                '52155123456',  // México móvil
                '12125551234',  // USA
                '34912345678',  // España fijo
                '34612345678',  // España móvil
                '442012345678', // Reino Unido fijo
                '447123456789', // Reino Unido móvil
                '33123456789',  // Francia fijo
                '5411234567',   // Argentina fijo
                '57112345678',  // Colombia fijo
                '81234567890'   // Japón (destino desconocido)
            ];
            
            CdrSimulado::create([
                'calldate' => $callDate,
                'clid' => $clids[array_rand($clids)],
                'src' => '1001',
                'dst' => $destinos[array_rand($destinos)],
                'dcontext' => 'from-internal',
                'channel' => 'SIP/1001-' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'dstchannel' => 'SIP/provider-' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'lastapp' => 'Dial',
                'lastdata' => 'SIP/provider/'. $destinos[array_rand($destinos)],
                'duration' => $duration,
                'billsec' => $billsec,
                'disposition' => rand(1, 10) > 2 ? 'ANSWERED' : (rand(1, 2) == 1 ? 'BUSY' : 'NO ANSWER'),
                'amaflags' => 3,
                'accountcode' => '',
                'uniqueid' => $callDate->timestamp . '.' . $i,
                'userfield' => '',
                'did' => '',
                'recordingfile' => '',
                'cnum' => '1001',
                'cnam' => '',
                'outbound_cnum' => '',
                'outbound_cnam' => '',
                'dst_cnam' => '',
                'linkedid' => $callDate->timestamp . '.' . $i,
                'peeraccount' => '',
                'sequence' => $i,
            ]);
        }
    }
}
