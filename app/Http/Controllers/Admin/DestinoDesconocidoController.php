<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinoDesconocido;
use App\Models\TarifaDestino;
use Illuminate\Http\Request;

class DestinoDesconocidoController extends Controller
{
    public function index()
    {
        $destinos = DestinoDesconocido::orderByDesc('cantidad_llamadas')->paginate(20);
        return view('admin.destinos.index', compact('destinos'));
    }

    public function marcarNotificado(DestinoDesconocido $destino)
    {
        $destino->update(['notificado' => true]);
        return back()->with('status', 'Destino marcado como notificado');
    }

    public function marcarProcesado(DestinoDesconocido $destino)
    {
        $destino->update(['procesado' => true]);
        return back()->with('status', 'Destino marcado como procesado');
    }

    public function crearTarifa(Request $request, DestinoDesconocido $destino)
    {
        $data = $request->validate([
            'destination' => ['required', 'string', 'max:255'],
            'codes' => ['required', 'string', 'max:32'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
        ]);

        TarifaDestino::create([
            'destination' => $data['destination'],
            'codes' => $data['codes'],
            'cost' => $data['cost'] ?? 0,
            'rate' => $data['rate'],
            'activo' => true,
        ]);

        $destino->update(['procesado' => true]);
        return back()->with('status', 'Tarifa creada y destino marcado como procesado');
    }
}
