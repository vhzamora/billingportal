@extends('layouts.app')

@section('title', 'Facturas')
@section('page-title', 'Facturación Mensual')

@section('content')
<div class="space-y-6">
  <form method="get" class="card p-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
    <div>
      <label class="block text-sm font-medium text-gray-700">Año</label>
      <input type="number" name="año" value="{{ $año }}" class="input mt-1">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Mes</label>
      <input type="number" name="mes" value="{{ $mes }}" class="input mt-1" min="1" max="12">
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700">Cliente</label>
      <select name="cliente_id" class="input mt-1">
        <option value="">Todos</option>
        @foreach($clientes as $c)
          <option value="{{ $c->id }}" {{ (string)$c->id === (string)$clienteId ? 'selected' : '' }}>{{ $c->nombre }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <button class="btn btn-secondary">Filtrar</button>
    </div>
  </form>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Llamadas</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Minutos</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Importe</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($facturas as $f)
            <tr>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $f->cliente?->nombre }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $f->año }}-{{ str_pad($f->mes,2,'0',STR_PAD_LEFT) }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $f->total_llamadas }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $f->total_minutos }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">$ {{ number_format($f->importe_total, 2) }}</td>
              <td class="px-4 py-2 text-right text-sm space-x-2">
                <a href="{{ route('admin.facturas.show', $f) }}" class="btn btn-secondary">Detalle</a>
                <a href="{{ route('admin.facturas.export.resumen', $f) }}" class="btn btn-secondary">Export Resumen</a>
                <a href="{{ route('admin.facturas.export.detalle', $f) }}" class="btn btn-primary">Export Detalle</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Sin facturas en el período.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $facturas->links() }}
  </div>
</div>
@endsection
