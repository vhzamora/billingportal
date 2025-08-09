@extends('layouts.app')

@section('title', 'Tarifas')
@section('page-title', 'Gestión de Tarifas')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <form class="flex gap-2" method="get">
      <input type="text" name="q" value="{{ $search }}" placeholder="Buscar destino o código" class="input w-64" />
      <button class="btn btn-secondary" type="submit">Buscar</button>
    </form>
    <a href="{{ route('admin.tarifas.create') }}" class="btn btn-primary">Nueva Tarifa</a>
  </div>

  @if(session('status'))
    <div class="card p-3 border border-green-200 bg-green-50 text-green-800">{{ session('status') }}</div>
  @endif

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Codes</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost (interno)</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rate (MXN/min)</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Activo</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($tarifas as $t)
            <tr>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $t->destination }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $t->codes }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($t->cost, 4) }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($t->rate, 4) }}</td>
              <td class="px-4 py-2 text-sm">{!! $t->activo ? '<span class="text-green-600">Sí</span>' : '<span class="text-gray-400">No</span>' !!}</td>
              <td class="px-4 py-2 text-right text-sm">
                <a href="{{ route('admin.tarifas.edit', $t) }}" class="text-blue-600 hover:underline mr-3">Editar</a>
                <form action="{{ route('admin.tarifas.destroy', $t) }}" method="post" class="inline" onsubmit="return confirm('¿Eliminar esta tarifa?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No hay tarifas registradas.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $tarifas->links() }}
  </div>
</div>
@endsection 