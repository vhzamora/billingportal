@extends('layouts.app')

@section('title', 'Nuevo Cliente')
@section('page-title', 'Nuevo Cliente')

@section('content')
<div class="max-w-3xl">
  <div class="card p-6 space-y-6">
    <form action="{{ route('admin.clientes.store') }}" method="post" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nombre</label>
          <input type="text" name="nombre" value="{{ old('nombre') }}" class="input mt-1" required>
          @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="input mt-1" required>
          @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Teléfono</label>
          <input type="text" name="telefono" value="{{ old('telefono') }}" class="input mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">RFC</label>
          <input type="text" name="rfc" value="{{ old('rfc') }}" class="input mt-1">
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Razón Social</label>
        <input type="text" name="razon_social" value="{{ old('razon_social') }}" class="input mt-1">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Dirección</label>
        <textarea name="direccion" class="input mt-1" rows="3">{{ old('direccion') }}</textarea>
      </div>
      <div class="flex items-center gap-3">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
          <input type="checkbox" name="activo" value="1" class="rounded border-gray-300" {{ old('activo', true) ? 'checked' : '' }}>
          Activo
        </label>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection 