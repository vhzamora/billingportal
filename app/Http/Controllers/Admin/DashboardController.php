<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\TarifaDestino;
use App\Models\CdrSimulado;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clientes' => Cliente::activos()->count(),
            'total_tarifas' => TarifaDestino::activos()->count(),
            'total_cdrs' => CdrSimulado::count(),
            'cdrs_answered' => CdrSimulado::where('disposition', 'ANSWERED')->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}
