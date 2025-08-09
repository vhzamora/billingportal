@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Gestión de Clientes')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <form method="get" class="flex gap-2">
      <input type="text" name="q" value="{{ $search }}" placeholder="Buscar nombre, email o RFC" class="input w-64" />
      <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>
    <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">Nuevo Cliente</a>
  </div>

  @if(session('status'))
    <div class="card p-3 border border-green-200 bg-green-50 text-green-800">{{ session('status') }}</div>
  @endif

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">RFC</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Activo</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($clientes as $c)
            <tr>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $c->nombre }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $c->email }}</td>
              <td class="px-4 py-2 text-sm text-gray-900">{{ $c->rfc }}</td>
              <td class="px-4 py-2 text-sm">{!! $c->activo ? '<span class="text-green-600">Sí</span>' : '<span class="text-gray-400">No</span>' !!}</td>
              <td class="px-4 py-2 text-right text-sm">
                <a href="{{ route('admin.clientes.edit', $c) }}" class="text-blue-600 hover:underline mr-3">Editar</a>
                <form action="{{ route('admin.clientes.destroy', $c) }}" method="post" class="inline" onsubmit="return confirm('¿Eliminar este cliente?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No hay clientes.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    {{ $clientes->links() }}
  </div>
</div>
@endsection 