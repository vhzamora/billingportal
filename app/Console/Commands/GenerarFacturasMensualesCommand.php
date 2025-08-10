<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\FacturaMensual;
use App\Models\LlamadaFacturada;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerarFacturasMensualesCommand extends Command
{
    protected $signature = 'facturas:generar {--año=} {--mes=} {--cliente_id=}';

    protected $description = 'Genera facturas mensuales por cliente a partir de llamadas facturadas';

    public function handle(): int
    {
        $año = $this->option('año') ? (int)$this->option('año') : (int) now()->year;
        $mes = $this->option('mes') ? (int)$this->option('mes') : (int) now()->month;
        $clienteId = $this->option('cliente_id');

        $inicio = Carbon::create($año, $mes, 1)->startOfDay();
        $fin = (clone $inicio)->endOfMonth()->endOfDay();
        $this->info("Generando facturas para {$año}-{$mes}");

        $clientes = Cliente::activos()
            ->when($clienteId, fn($q) => $q->where('id', $clienteId))
            ->get();

        foreach ($clientes as $cliente) {
            $base = LlamadaFacturada::where('cliente_id', $cliente->id)
                ->whereBetween('fecha_llamada', [$inicio, $fin]);

            $totalLlamadas = (clone $base)->count();
            $totalMin = (clone $base)->sum('minutos_facturados');
            $totalImporte = (clone $base)->sum('costo_cliente');

            if ($totalLlamadas === 0) {
                $this->line("- Cliente {$cliente->id} no tiene llamadas en el periodo");
                continue;
            }

            FacturaMensual::updateOrCreate(
                ['cliente_id' => $cliente->id, 'año' => $año, 'mes' => $mes],
                [
                    'total_llamadas' => $totalLlamadas,
                    'total_minutos' => (int) $totalMin,
                    'importe_total' => (float) $totalImporte,
                    'generada_at' => now(),
                ]
            );

            $this->line("- Cliente {$cliente->id}: llamadas {$totalLlamadas}, minutos {$totalMin}, importe {$totalImporte}");
        }

        $this->info('Facturas generadas/actualizadas.');
        return self::SUCCESS;
    }
}
