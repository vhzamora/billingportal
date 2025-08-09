<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\LlamadaFacturada;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LlamadaController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->date('desde') ?? Carbon::now()->startOfMonth();
        $hasta = $request->date('hasta') ?? Carbon::now()->endOfMonth();
        $clienteId = $request->integer('cliente_id') ?: null;
        $soloDesconocidos = $request->boolean('desconocidos');

        $query = LlamadaFacturada::query()
            ->when($desde, fn($q) => $q->where('fecha_llamada', '>=', $desde))
            ->when($hasta, fn($q) => $q->where('fecha_llamada', '<=', $hasta))
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId))
            ->when($soloDesconocidos, fn($q) => $q->where('es_destino_desconocido', true))
            ->orderByDesc('fecha_llamada');

        $llamadas = $query->paginate(20)->withQueryString();
        $clientes = Cliente::activos()->orderBy('nombre')->get(['id','nombre']);

        return view('admin.llamadas.index', compact('llamadas', 'clientes', 'desde', 'hasta', 'clienteId', 'soloDesconocidos'));
    }
}
