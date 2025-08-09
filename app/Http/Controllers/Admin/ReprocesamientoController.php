<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class ReprocesamientoController extends Controller
{
    public function form()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get(['id','nombre']);
        return view('admin.reproceso.form', compact('clientes'));
    }

    public function run(Request $request)
    {
        $data = $request->validate([
            'desde' => ['required', 'date'],
            'hasta' => ['required', 'date', 'after_or_equal:desde'],
            'cliente_id' => ['nullable', 'integer', 'exists:clientes,id'],
            'force' => ['nullable', 'boolean'],
        ]);

        $desde = Carbon::parse($data['desde'])->startOfDay();
        $hasta = Carbon::parse($data['hasta'])->endOfDay();
        $params = [
            '--desde' => $desde->toDateTimeString(),
            '--hasta' => $hasta->toDateTimeString(),
        ];
        if (!empty($data['cliente_id'])) $params['--cliente_id'] = $data['cliente_id'];
        if (!empty($data['force'])) $params['--force-reprocess'] = true;

        Artisan::call('cdrs:procesar', $params);
        $output = Artisan::output();

        return back()->with('status', "Reproceso ejecutado. Output: \n".$output);
    }
}
