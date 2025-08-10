<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\FacturaMensual;
use App\Models\LlamadaFacturada;
use App\Exports\LlamadasClienteExport;
use App\Exports\ResumenPeriodoClienteExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $año = $request->integer('año') ?: now()->year;
        $mes = $request->integer('mes') ?: now()->month;
        $clienteId = $request->integer('cliente_id') ?: null;

        $facturas = FacturaMensual::with('cliente')
            ->when($año, fn($q)=>$q->where('año', $año))
            ->when($mes, fn($q)=>$q->where('mes', $mes))
            ->when($clienteId, fn($q)=>$q->where('cliente_id', $clienteId))
            ->orderByDesc('año')->orderByDesc('mes')
            ->paginate(20)->withQueryString();

        $clientes = Cliente::activos()->orderBy('nombre')->get(['id','nombre']);
        return view('admin.facturas.index', compact('facturas','clientes','año','mes','clienteId'));
    }

    public function show(FacturaMensual $factura)
    {
        $inicio = Carbon::create($factura->año, $factura->mes, 1)->startOfDay();
        $fin = (clone $inicio)->endOfMonth()->endOfDay();
        $llamadas = LlamadaFacturada::where('cliente_id', $factura->cliente_id)
            ->whereBetween('fecha_llamada', [$inicio, $fin])
            ->orderBy('fecha_llamada')
            ->paginate(50);

        return view('admin.facturas.show', compact('factura','llamadas'));
    }

    public function exportResumen(FacturaMensual $factura)
    {
        $inicio = Carbon::create($factura->año, $factura->mes, 1)->startOfDay();
        $fin = (clone $inicio)->endOfMonth()->endOfDay();
        $file = "resumen_{$factura->cliente_id}_{$factura->año}_{$factura->mes}.xlsx";
        return Excel::download(new ResumenPeriodoClienteExport($factura->cliente_id, $inicio, $fin), $file);
    }

    public function exportDetalle(FacturaMensual $factura)
    {
        $inicio = Carbon::create($factura->año, $factura->mes, 1)->startOfDay();
        $fin = (clone $inicio)->endOfMonth()->endOfDay();
        $file = "detalle_{$factura->cliente_id}_{$factura->año}_{$factura->mes}.xlsx";
        return Excel::download(new LlamadasClienteExport($factura->cliente_id, $inicio, $fin), $file);
    }
}
