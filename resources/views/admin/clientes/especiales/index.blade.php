@extends('layouts.app')

@section('title', 'Tarifas especiales')
@section('page-title', 'Tarifas especiales de ' . $cliente->nombre)

@section('content')
<div class="space-y-6">
  @if(session('status'))
    <div class="card p-3 border border-green-200 bg-green-50 text-green-800">{{ session('status') }}</div>
  @endif

  <div class="card p-6 space-y-4">
    <form action="{{ route('admin.clientes.especiales.index', $cliente) }}" method="get" class="flex gap-2">
      <input type="text" name="q" value="{{ $search }}" placeholder="Buscar destino/código" class="input w-64">
      <button class="btn btn-secondary">Buscar</button>
      <a href="{{ route('admin.tarifas.index') }}" class="btn btn-secondary">Ver tarifas generales</a>
    </form>

    <form action="{{ route('admin.clientes.especiales.store', $cliente) }}" method="post" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
      @csrf
      <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700">Seleccionar destino (Tarifa general)</label>
        <select name="tarifa_destino_id" class="input mt-1" required>
          <option value="">Selecciona</option>
          @php($list = \App\Models\TarifaDestino::activos()
              ->when($search, fn($q)=>$q->where('destination','like',"%{$search}%")->orWhere('codes','like',"%{$search}%"))
              ->orderBy('destination')->limit(200)->get())
          @foreach($list as $t)
            <option value="{{ $t->id }}">{{ $t->destination }} ({{ $t->codes }}) · Rate: {{ number_format($t->rate,4) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Rate personalizado (MXN/min)</label>
        <input type="number" step="0.0001" name="rate_personalizado" class="input mt-1" required>
      </div>
      <div>
        <button class="btn btn-primary w-full" type="submit">Agregar/Actualizar</button>
      </div>
    </form>
  </div>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rate general</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rate personalizado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Activo</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($especiales as $e)
            <tr>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $e->tarifaDestino->destination }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $e->tarifaDestino->codes }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($e->tarifaDestino->rate, 4) }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($e->rate_personalizado, 4) }}</td>
              <td class="px-4 py-2 text-sm">{!! $e->activo ? '<span class="text-green-600">Sí</span>' : '<span class="text-gray-400">No</span>' !!}</td>
              <td class="px-4 py-2 text-right text-sm space-x-2">
                <form action="{{ route('admin.clientes.especiales.toggle', [$cliente, $e]) }}" method="post" class="inline">
                  @csrf
                  @method('PUT')
                  <button class="btn btn-secondary" type="submit">{{ $e->activo ? 'Desactivar' : 'Activar' }}</button>
                </form>
                <form action="{{ route('admin.clientes.especiales.destroy', [$cliente, $e]) }}" method="post" class="inline" onsubmit="return confirm('¿Eliminar tarifa especial?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Sin tarifas especiales.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $especiales->links() }}
  </div>
</div>
@endsection 