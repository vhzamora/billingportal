<?php

namespace App\Console\Commands;

use App\Models\CdrSimulado;
use App\Models\Cliente;
use App\Models\ClienteClid;
use App\Models\DestinoDesconocido;
use App\Models\LlamadaFacturada;
use App\Models\TarifaDestino;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcesarCdrsCommand extends Command
{
    protected $signature = 'cdrs:procesar {--desde=} {--hasta=} {--cliente_id=} {--force-reprocess}';

    protected $description = 'Procesa CDRs (simulados) para generar llamadas facturadas';

    public function handle(): int
    {
        $desde = $this->option('desde') ? Carbon::parse($this->option('desde')) : now()->startOfMonth();
        $hasta = $this->option('hasta') ? Carbon::parse($this->option('hasta')) : now()->endOfMonth();
        $clienteIdFilter = $this->option('cliente_id');
        $force = (bool) $this->option('force-reprocess');

        $this->info("Procesando CDRs desde {$desde} hasta {$hasta}" . ($clienteIdFilter ? " para cliente {$clienteIdFilter}" : ''));

        $query = CdrSimulado::query()
            ->whereBetween('calldate', [$desde, $hasta])
            ->where('disposition', 'ANSWERED')
            ->where('billsec', '>', 0);

        $total = $query->count();
        $this->info("Total CDRs a evaluar: {$total}");

        $processed = 0;
        $batch = 200;

        $query->orderBy('calldate')->chunk($batch, function($rows) use (&$processed, $clienteIdFilter, $force) {
            foreach ($rows as $cdr) {
                // Saltar si ya procesado salvo force
                if (!$force && LlamadaFacturada::where('cdr_uniqueid', $cdr->uniqueid)->exists()) {
                    continue;
                }

                // Mapear cliente por CLID exacto
                $clid = $cdr->clid;
                $clienteClid = ClienteClid::where('clid', $clid)->where('activo', true)->first();
                if (!$clienteClid) {
                    // No asignado; registrar como destino desconocido para revisión? Aquí solo skip
                    continue;
                }
                $cliente = $clienteClid->cliente;
                if ($clienteIdFilter && (int)$clienteIdFilter !== (int)$cliente->id) {
                    continue;
                }

                $numeroDestino = preg_replace('/[^0-9]/', '', $cdr->dst);
                $tarifaMatch = TarifaDestino::buscarTarifaPorNumero($numeroDestino, $cliente->id);

                $minutos = (int) ceil(max(0, $cdr->billsec) / 60);
                $esDesconocido = false;
                $destination = null;
                $codigoMatched = null;
                $rate = 0;

                if ($tarifaMatch) {
                    $destination = $tarifaMatch['tarifa']->destination;
                    $codigoMatched = $tarifaMatch['codigo_matched'];
                    $rate = (float) $tarifaMatch['rate'];
                } else {
                    $esDesconocido = true;
                    // Registrar destino desconocido para alertar admin
                    $dd = DestinoDesconocido::firstOrCreate(
                        ['numero_destino' => $numeroDestino],
                        ['cantidad_llamadas' => 0]
                    );
                    $dd->increment('cantidad_llamadas');
                }

                LlamadaFacturada::updateOrCreate(
                    ['cdr_uniqueid' => $cdr->uniqueid],
                    [
                        'cliente_id' => $cliente->id,
                        'clid' => $clid,
                        'numero_origen' => $cdr->src,
                        'numero_destino' => $cdr->dst,
                        'fecha_llamada' => $cdr->calldate,
                        'duracion_segundos' => $cdr->billsec,
                        'minutos_facturados' => $minutos,
                        'destination_encontrado' => $destination,
                        'codigo_matched' => $codigoMatched,
                        'tarifa_aplicada' => $rate,
                        'costo_cliente' => round($rate * $minutos, 4),
                        'es_destino_desconocido' => $esDesconocido,
                        'reprocesado' => $force,
                        'reprocesado_at' => $force ? now() : null,
                        'procesado_at' => now(),
                    ]
                );

                $processed++;
            }
        });

        $this->info("CDRs procesados: {$processed}");
        return self::SUCCESS;
    }
}
