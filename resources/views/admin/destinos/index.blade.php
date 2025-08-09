@extends('layouts.app')

@section('title', 'Destinos Desconocidos')
@section('page-title', 'Destinos Desconocidos')

@section('content')
<div class="space-y-6">
  @if(session('status'))
    <div class="card p-3 border border-green-200 bg-green-50 text-green-800">{!! nl2br(e(session('status'))) !!}</div>
  @endif

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Número Destino</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Llamadas</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notificado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Procesado</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($destinos as $d)
          <tr>
            <td class="px-4 py-2 text-sm">{{ $d->numero_destino }}</td>
            <td class="px-4 py-2 text-sm">{{ $d->cantidad_llamadas }}</td>
            <td class="px-4 py-2 text-sm">{!! $d->notificado ? '<span class="text-green-600">Sí</span>' : '<span class="text-gray-400">No</span>' !!}</td>
            <td class="px-4 py-2 text-sm">{!! $d->procesado ? '<span class="text-green-600">Sí</span>' : '<span class="text-gray-400">No</span>' !!}</td>
            <td class="px-4 py-2 text-right text-sm space-x-2">
              <form action="{{ route('admin.destinos.notificar', $d) }}" method="post" class="inline">
                @csrf
                @method('PUT')
                <button class="btn btn-secondary" type="submit">Marcar notificado</button>
              </form>
              <form action="{{ route('admin.destinos.procesado', $d) }}" method="post" class="inline">
                @csrf
                @method('PUT')
                <button class="btn btn-secondary" type="submit">Marcar procesado</button>
              </form>
            </td>
          </tr>
          <tr>
            <td colspan="5" class="px-4 pb-4">
              <form action="{{ route('admin.destinos.crear-tarifa', $d) }}" method="post" class="grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                @csrf
                <div>
                  <label class="block text-xs text-gray-600">Destination</label>
                  <input type="text" name="destination" class="input" placeholder="Ej. México Móvil" required>
                </div>
                <div>
                  <label class="block text-xs text-gray-600">Codes</label>
                  <input type="text" name="codes" class="input" placeholder="Ej. 521, 5255" required>
                </div>
                <div>
                  <label class="block text-xs text-gray-600">Cost</label>
                  <input type="number" step="0.0001" name="cost" class="input" placeholder="0.0000">
                </div>
                <div>
                  <label class="block text-xs text-gray-600">Rate (MXN/min)</label>
                  <input type="number" step="0.0001" name="rate" class="input" placeholder="1.0000" required>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary w-full">Crear tarifa</button>
                </div>
              </form>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Sin destinos desconocidos.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $destinos->links() }}
  </div>
</div>
@endsection 