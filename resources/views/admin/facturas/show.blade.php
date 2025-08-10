@extends('layouts.app')

@section('title', 'Factura')
@section('page-title', 'Factura ' . $factura->año . '-' . str_pad($factura->mes,2,'0',STR_PAD_LEFT) . ' · ' . ($factura->cliente?->nombre ?? ''))

@section('content')
<div class="space-y-6">
  <div class="card p-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div>
      <div class="text-sm text-gray-500">Cliente</div>
      <div class="text-lg font-medium">{{ $factura->cliente?->nombre }}</div>
    </div>
    <div>
      <div class="text-sm text-gray-500">Periodo</div>
      <div class="text-lg font-medium">{{ $factura->año }}-{{ str_pad($factura->mes,2,'0',STR_PAD_LEFT) }}</div>
    </div>
    <div>
      <div class="text-sm text-gray-500">Minutos</div>
      <div class="text-lg font-medium">{{ $factura->total_minutos }}</div>
    </div>
    <div>
      <div class="text-sm text-gray-500">Importe</div>
      <div class="text-lg font-medium">$ {{ number_format($factura->importe_total, 2) }}</div>
    </div>
  </div>

  <div class="flex gap-3">
    <a href="{{ route('admin.facturas.export.resumen', $factura) }}" class="btn btn-secondary">Exportar Resumen</a>
    <a href="{{ route('admin.facturas.export.detalle', $factura) }}" class="btn btn-primary">Exportar Detalle</a>
  </div>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">CLID</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Origen</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Min</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Tarifa</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Importe</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @foreach($llamadas as $l)
            <tr>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->fecha_llamada->format('Y-m-d H:i') }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->clid }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->numero_origen }}</td>
              <td class="px-3 py-2 text-sm text-gray-900">{{ $l->numero_destino }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ $l->minutos_facturados }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ number_format($l->tarifa_aplicada,4) }}</td>
              <td class="px-3 py-2 text-sm text-right">{{ number_format($l->costo_cliente,4) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $llamadas->links() }}
  </div>
</div>
@endsection
