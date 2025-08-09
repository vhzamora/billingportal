@extends('layouts.app')

@section('title', 'Llamadas')
@section('page-title', 'Llamadas Facturadas')

@section('content')
<div class="space-y-6">
  <form method="get" class="card p-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
    <div>
      <label class="block text-sm font-medium text-gray-700">Desde</label>
      <input type="date" name="desde" value="{{ request('desde', optional($desde)->format('Y-m-d')) }}" class="input mt-1">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Hasta</label>
      <input type="date" name="hasta" value="{{ request('hasta', optional($hasta)->format('Y-m-d')) }}" class="input mt-1">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Cliente</label>
      <select name="cliente_id" class="input mt-1">
        <option value="">Todos</option>
        @foreach($clientes as $c)
          <option value="{{ $c->id }}" {{ (string)$c->id === request('cliente_id') ? 'selected' : '' }}>{{ $c->nombre }}</option>
        @endforeach
      </select>
    </div>
    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
      <input type="checkbox" name="desconocidos" value="1" class="rounded border-gray-300" {{ request('desconocidos') ? 'checked' : '' }}>
      Solo destinos desconocidos
    </label>
    <div>
      <button class="btn btn-secondary">Filtrar</button>
    </div>
  </form>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">CLID</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Origen</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Seg</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Min</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tarifa</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Importe</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destino Detectado</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @php($totalImporte = 0)
          @forelse($llamadas as $l)
            @php($totalImporte += $l->costo_cliente)
            <tr>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->fecha_llamada->format('Y-m-d H:i') }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ optional($l->cliente)->nombre }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->clid }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->numero_origen }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->numero_destino }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ $l->duracion_segundos }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ $l->minutos_facturados }}</td>
              <td class="px-3 py-2 text-sm">{{ number_format($l->tarifa_aplicada, 4) }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ number_format($l->costo_cliente, 4) }}</td>
              <td class="px-3 py-2 text-sm">{!! $l->es_destino_desconocido ? '<span class="text-red-600">Desconocido</span>' : e($l->destination_encontrado) !!}</td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="px-3 py-6 text-center text-sm text-gray-500">No hay llamadas para los filtros seleccionados.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex items-center justify-between">
    <div class="text-sm text-gray-600">Mostrando {{ $llamadas->count() }} de {{ $llamadas->total() }} llamadas</div>
    <div class="text-sm font-medium">Total: $ {{ number_format($totalImporte, 2) }}</div>
  </div>

  <div>
    {{ $llamadas->links() }}
  </div>
</div>
@endsection 