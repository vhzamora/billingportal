<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\LlamadaFacturada;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LlamadasClienteExport;
use App\Exports\ResumenPeriodoClienteExport;

class ClienteDashboardController extends Controller
{
    public function dashboard(Request $request, Cliente $cliente)
    {
        $desde = $request->date('desde') ?? Carbon::now()->startOfMonth();
        $hasta = $request->date('hasta') ?? Carbon::now()->endOfMonth();

        $baseQuery = LlamadaFacturada::where('cliente_id', $cliente->id)
            ->whereBetween('fecha_llamada', [$desde, $hasta]);

        $totalLlamadas = (clone $baseQuery)->count();
        $totalSegundos = (clone $baseQuery)->sum('duracion_segundos');
        $totalMinutos = (clone $baseQuery)->sum('minutos_facturados');
        $totalImporte = (clone $baseQuery)->sum('costo_cliente');
        $desconocidos = (clone $baseQuery)->where('es_destino_desconocido', true)->count();

        $topDestinos = (clone $baseQuery)
            ->selectRaw('COALESCE(destination_encontrado, "Desconocido") as dest, COUNT(*) as llamadas, SUM(minutos_facturados) as minutos, SUM(costo_cliente) as importe')
            ->groupBy('dest')
            ->orderByDesc('importe')
            ->limit(5)
            ->get();

        $recientes = (clone $baseQuery)
            ->orderByDesc('fecha_llamada')
            ->limit(10)
            ->get();

        return view('admin.clientes.dashboard', compact(
            'cliente', 'desde', 'hasta',
            'totalLlamadas', 'totalSegundos', 'totalMinutos', 'totalImporte', 'desconocidos',
            'topDestinos', 'recientes'
        ));
    }

    public function exportDetalle(Request $request, Cliente $cliente)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $desdeDate = $desde ? Carbon::parse($desde)->startOfDay() : Carbon::now()->startOfMonth();
        $hastaDate = $hasta ? Carbon::parse($hasta)->endOfDay() : Carbon::now()->endOfMonth();

        $file = 'detalle_llamadas_'.$cliente->id.'_'.Carbon::now()->format('Ymd_His').'.xlsx';
        return Excel::download(new LlamadasClienteExport($cliente->id, $desdeDate, $hastaDate), $file);
    }

    public function exportPeriodo(Request $request, Cliente $cliente)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $desdeDate = $desde ? Carbon::parse($desde)->startOfDay() : Carbon::now()->startOfMonth();
        $hastaDate = $hasta ? Carbon::parse($hasta)->endOfDay() : Carbon::now()->endOfMonth();

        $file = 'resumen_facturacion_'.$cliente->id.'_'.Carbon::now()->format('Ymd_His').'.xlsx';
        return Excel::download(new ResumenPeriodoClienteExport($cliente->id, $desdeDate, $hastaDate), $file);
    }
}
