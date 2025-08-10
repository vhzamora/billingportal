<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\CdrSimulado;
use App\Models\LlamadaFacturada;
use App\Models\TarifaDestino;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        // KPIs básicos
        $stats = [
            'total_clientes' => Cliente::activos()->count(),
            'total_tarifas' => TarifaDestino::activos()->count(),
            'total_cdrs' => CdrSimulado::count(),
            'cdrs_answered' => CdrSimulado::where('disposition', 'ANSWERED')->count(),
        ];

        // Facturación del mes por día
        $facturacionPorDia = LlamadaFacturada::selectRaw('date(fecha_llamada) as dia, SUM(costo_cliente) as importe')
            ->whereBetween('fecha_llamada', [$inicioMes, $finMes])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        // Utilidad: (tarifa - costo)*minutos
        $llamadasMes = LlamadaFacturada::whereBetween('fecha_llamada', [$inicioMes, $finMes])
            ->get(['fecha_llamada','minutos_facturados','tarifa_aplicada','codigo_matched']);
        $codigos = $llamadasMes->pluck('codigo_matched')->filter()->unique()->values();
        $costosPorCodigo = TarifaDestino::whereIn('codes', $codigos)->pluck('cost','codes');

        $utilidadPorDiaMap = [];
        foreach ($llamadasMes as $l) {
            $dia = Carbon::parse($l->fecha_llamada)->toDateString();
            $costoMin = (float) ($costosPorCodigo[$l->codigo_matched] ?? 0);
            $utilidad = max(0, (float)$l->tarifa_aplicada - $costoMin) * (int)$l->minutos_facturados;
            $utilidadPorDiaMap[$dia] = ($utilidadPorDiaMap[$dia] ?? 0) + $utilidad;
        }
        ksort($utilidadPorDiaMap);

        // Top destinos
        $topDestinos = LlamadaFacturada::selectRaw('COALESCE(destination_encontrado, "Desconocido") as destino, SUM(costo_cliente) as importe, SUM(minutos_facturados) as minutos, COUNT(*) as llamadas')
            ->whereBetween('fecha_llamada', [$inicioMes, $finMes])
            ->groupBy('destino')
            ->orderByDesc('importe')
            ->limit(10)
            ->get();

        // Top clientes por facturación
        $topClientes = LlamadaFacturada::selectRaw('cliente_id, SUM(costo_cliente) as importe, SUM(minutos_facturados) as minutos, COUNT(*) as llamadas')
            ->whereBetween('fecha_llamada', [$inicioMes, $finMes])
            ->groupBy('cliente_id')
            ->orderByDesc('importe')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->cliente_nombre = optional(Cliente::find($row->cliente_id))->nombre;
                return $row;
            });

        // Totales del mes
        $totalMesImporte = $facturacionPorDia->sum('importe');
        $totalMesUtilidad = array_sum($utilidadPorDiaMap);

        // Series para gráficas (facturación/utilidad)
        $facturacionSeries = $facturacionPorDia->map(function ($r) {
            return [
                'x' => $r->dia,
                'y' => round((float) $r->importe, 2),
            ];
        })->values();
        $utilidadSeries = collect($utilidadPorDiaMap)->map(function ($v, $k) {
            return [
                'x' => $k,
                'y' => round((float) $v, 2),
            ];
        })->values();

        // Series para gráficas (llamadas vs completadas) desde CDR simulado
        $llamadasTotales = CdrSimulado::selectRaw('date(calldate) as dia, COUNT(*) as total')
            ->whereBetween('calldate', [$inicioMes, $finMes])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();
        $llamadasCompletadas = CdrSimulado::selectRaw('date(calldate) as dia, COUNT(*) as total')
            ->whereBetween('calldate', [$inicioMes, $finMes])
            ->where('disposition', 'ANSWERED')
            ->where('billsec', '>', 0)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();
        $llamadasSeries = $llamadasTotales->map(fn($r)=>['x'=>$r->dia, 'y'=>(int)$r->total])->values();
        $completadasSeries = $llamadasCompletadas->map(fn($r)=>['x'=>$r->dia, 'y'=>(int)$r->total])->values();

        // Pie chart data for top clients
        $topClientesLabels = $topClientes->map(function ($r) {
            return $r->cliente_nombre ?: ('Cliente #'.$r->cliente_id);
        })->values();
        $topClientesImporte = $topClientes->pluck('importe')->map(fn($v)=>(float)$v)->values();

        return view('admin.dashboard', [
            'stats' => $stats,
            'facturacionPorDia' => $facturacionPorDia,
            'utilidadPorDiaMap' => $utilidadPorDiaMap,
            'topDestinos' => $topDestinos,
            'topClientes' => $topClientes,
            'totalMesImporte' => $totalMesImporte,
            'totalMesUtilidad' => $totalMesUtilidad,
            'inicioMes' => $inicioMes,
            'finMes' => $finMes,
            'facturacionSeries' => $facturacionSeries,
            'utilidadSeries' => $utilidadSeries,
            'topClientesLabels' => $topClientesLabels,
            'topClientesImporte' => $topClientesImporte,
            'llamadasSeries' => $llamadasSeries,
            'completadasSeries' => $completadasSeries,
        ]);
    }
}
