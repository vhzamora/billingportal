<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifaDestino;
use Illuminate\Http\Request;

class TarifaDestinoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();
        $tarifas = TarifaDestino::query()
            ->when($search, function($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhere('codes', 'like', "%{$search}%");
            })
            ->orderBy('codes')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tarifas.index', compact('tarifas', 'search'));
    }

    public function create()
    {
        return view('admin.tarifas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'destination' => ['required', 'string', 'max:255'],
            'codes' => ['required', 'string', 'max:32'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo', true);
        TarifaDestino::create($data);

        return redirect()->route('admin.tarifas.index')->with('status', 'Tarifa creada correctamente');
    }

    public function edit(TarifaDestino $tarifa)
    {
        return view('admin.tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request, TarifaDestino $tarifa)
    {
        $data = $request->validate([
            'destination' => ['required', 'string', 'max:255'],
            'codes' => ['required', 'string', 'max:32'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo', true);
        $tarifa->update($data);

        return redirect()->route('admin.tarifas.index')->with('status', 'Tarifa actualizada');
    }

    public function destroy(TarifaDestino $tarifa)
    {
        $tarifa->delete();
        return redirect()->route('admin.tarifas.index')->with('status', 'Tarifa eliminada');
    }
}
