@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page-title', 'Editar Cliente')

@section('content')
<div class="space-y-8">
  @if(session('status'))
    <div class="card p-3 border border-green-200 bg-green-50 text-green-800">{{ session('status') }}</div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
      <div class="card p-6 space-y-6">
        <form action="{{ route('admin.clientes.update', $cliente) }}" method="post" class="space-y-4">
          @csrf
          @method('PUT')
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nombre</label>
              <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" class="input mt-1" required>
              @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="input mt-1" required>
              @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Teléfono</label>
              <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="input mt-1">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">RFC</label>
              <input type="text" name="rfc" value="{{ old('rfc', $cliente->rfc) }}" class="input mt-1">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Razón Social</label>
            <input type="text" name="razon_social" value="{{ old('razon_social', $cliente->razon_social) }}" class="input mt-1">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Dirección</label>
            <textarea name="direccion" class="input mt-1" rows="3">{{ old('direccion', $cliente->direccion) }}</textarea>
          </div>
          <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input type="checkbox" name="activo" value="1" class="rounded border-gray-300" {{ old('activo', $cliente->activo) ? 'checked' : '' }}>
              Activo
            </label>
          </div>
          <div class="flex items-center gap-3">
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>

    <div class="lg:col-span-1">
      <div class="card p-6 space-y-4">
        <h3 class="text-lg font-medium text-gray-900">CLIDs del Cliente</h3>
        <form action="{{ route('admin.clientes.clids.add', $cliente) }}" method="post" class="flex gap-2">
          @csrf
          <input type="text" name="clid" placeholder="Ej: &quot;ABC Ventas&quot; &lt;1002&gt;" class="input flex-1" required>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
        <div class="divide-y divide-gray-200">
          @forelse($cliente->clids as $clid)
            <div class="flex items-center justify-between py-2">
              <div class="text-sm text-gray-800">{{ $clid->clid }}</div>
              <div class="flex items-center gap-3 text-sm">
                <form action="{{ route('admin.clientes.clids.toggle', [$cliente, $clid]) }}" method="post">
                  @csrf
                  @method('PUT')
                  <button class="{{ $clid->activo ? 'text-green-600' : 'text-gray-400' }} hover:underline" type="submit">
                    {{ $clid->activo ? 'Activo' : 'Inactivo' }}
                  </button>
                </form>
                <form action="{{ route('admin.clientes.clids.delete', [$cliente, $clid]) }}" method="post" onsubmit="return confirm('¿Eliminar este CLID?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline" type="submit">Eliminar</button>
                </form>
              </div>
            </div>
          @empty
            <div class="py-4 text-sm text-gray-500">Aún no hay CLIDs asignados.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 