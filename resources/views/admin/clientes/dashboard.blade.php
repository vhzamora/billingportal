@extends('layouts.app')

@section('title', 'Dashboard Cliente')
@section('page-title', 'Dashboard de ' . $cliente->nombre)

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
    <div class="flex gap-3">
      <button class="btn btn-secondary">Actualizar</button>
      <a href="{{ route('admin.cliente.export.detalle', $cliente) }}?desde={{ request('desde') }}&hasta={{ request('hasta') }}" class="btn btn-secondary">Exportar Detalle</a>
      <a href="{{ route('admin.cliente.export.periodo', $cliente) }}?desde={{ request('desde') }}&hasta={{ request('hasta') }}" class="btn btn-primary">Exportar Resumen</a>
    </div>
  </form>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="card p-6">
      <div class="text-sm text-gray-500">Llamadas</div>
      <div class="text-2xl font-semibold">{{ $totalLlamadas }}</div>
    </div>
    <div class="card p-6">
      <div class="text-sm text-gray-500">Minutos</div>
      <div class="text-2xl font-semibold">{{ $totalMinutos }}</div>
    </div>
    <div class="card p-6">
      <div class="text-sm text-gray-500">Importe</div>
      <div class="text-2xl font-semibold">$ {{ number_format($totalImporte, 2) }}</div>
    </div>
    <div class="card p-6">
      <div class="text-sm text-gray-500">Destinos Desconocidos</div>
      <div class="text-2xl font-semibold">{{ $desconocidos }}</div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Top Destinos</h3>
      <div class="space-y-2">
        @forelse($topDestinos as $row)
          <div class="flex items-center justify-between text-sm">
            <div>{{ $row->dest }}</div>
            <div class="text-gray-600">{{ $row->llamadas }} llamadas · {{ $row->minutos }} min · $ {{ number_format($row->importe, 2) }}</div>
          </div>
        @empty
          <div class="text-sm text-gray-500">Sin datos.</div>
        @endforelse
      </div>
    </div>

    <div class="card p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Llamadas Recientes</h3>
      <div class="space-y-2">
        @forelse($recientes as $l)
          <div class="flex items-center justify-between text-sm">
            <div>{{ $l->fecha_llamada->format('Y-m-d H:i') }} · {{ $l->numero_destino }}</div>
            <div class="text-gray-600">{{ $l->minutos_facturados }} min · $ {{ number_format($l->costo_cliente, 2) }}</div>
          </div>
        @empty
          <div class="text-sm text-gray-500">Sin datos.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection 