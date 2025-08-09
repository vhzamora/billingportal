<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClienteTarifaEspecial;
use App\Models\TarifaDestino;
use Illuminate\Http\Request;

class ClienteTarifaEspecialController extends Controller
{
    public function index(Cliente $cliente, Request $request)
    {
        $search = $request->string('q')->toString();
        $especiales = ClienteTarifaEspecial::with('tarifaDestino')
            ->where('cliente_id', $cliente->id)
            ->when($search, function($q) use ($search) {
                $q->whereHas('tarifaDestino', function($qq) use ($search) {
                    $qq->where('destination', 'like', "%{$search}%")
                       ->orWhere('codes', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)->withQueryString();

        return view('admin.clientes.especiales.index', compact('cliente','especiales','search'));
    }

    public function store(Cliente $cliente, Request $request)
    {
        $data = $request->validate([
            'tarifa_destino_id' => ['required','integer','exists:tarifas_destinos,id'],
            'rate_personalizado' => ['required','numeric','min:0'],
        ]);

        ClienteTarifaEspecial::updateOrCreate(
            ['cliente_id' => $cliente->id, 'tarifa_destino_id' => $data['tarifa_destino_id']],
            ['rate_personalizado' => $data['rate_personalizado'], 'activo' => true]
        );

        return back()->with('status', 'Tarifa especial guardada');
    }

    public function toggle(Cliente $cliente, ClienteTarifaEspecial $especial)
    {
        abort_unless($especial->cliente_id === $cliente->id, 404);
        $especial->update(['activo' => !$especial->activo]);
        return back()->with('status', 'Tarifa especial actualizada');
    }

    public function destroy(Cliente $cliente, ClienteTarifaEspecial $especial)
    {
        abort_unless($especial->cliente_id === $cliente->id, 404);
        $especial->delete();
        return back()->with('status', 'Tarifa especial eliminada');
    }
} 