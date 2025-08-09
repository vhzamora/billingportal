<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClienteClid;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();
        $clientes = Cliente::query()
            ->when($search, function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes', 'search'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clientes,email'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo', true);
        $cliente = Cliente::create($data);

        return redirect()->route('admin.clientes.edit', $cliente)->with('status', 'Cliente creado');
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('clids');
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clientes,email,'.$cliente->id],
            'telefono' => ['nullable', 'string', 'max:50'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo', true);
        $cliente->update($data);

        return redirect()->route('admin.clientes.edit', $cliente)->with('status', 'Cliente actualizado');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.clientes.index')->with('status', 'Cliente eliminado');
    }

    // CLIDs
    public function addClid(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'clid' => ['required', 'string', 'max:255'],
        ]);

        $exists = $cliente->clids()->where('clid', $data['clid'])->exists();
        if (!$exists) {
            $cliente->clids()->create([
                'clid' => $data['clid'],
                'activo' => true,
            ]);
        }

        return back()->with('status', 'CLID agregado');
    }

    public function toggleClid(Cliente $cliente, ClienteClid $clid)
    {
        abort_unless($clid->cliente_id === $cliente->id, 404);
        $clid->update(['activo' => !$clid->activo]);
        return back()->with('status', 'CLID actualizado');
    }

    public function deleteClid(Cliente $cliente, ClienteClid $clid)
    {
        abort_unless($clid->cliente_id === $cliente->id, 404);
        $clid->delete();
        return back()->with('status', 'CLID eliminado');
    }
}
