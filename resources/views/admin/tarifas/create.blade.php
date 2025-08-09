@extends('layouts.app')

@section('title', 'Nueva Tarifa')
@section('page-title', 'Nueva Tarifa')

@section('content')
<div class="max-w-3xl">
  <div class="card p-6 space-y-6">
    <form action="{{ route('admin.tarifas.store') }}" method="post" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700">Destination</label>
        <input type="text" name="destination" value="{{ old('destination') }}" class="input mt-1" required>
        @error('destination')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Codes</label>
        <input type="text" name="codes" value="{{ old('codes') }}" class="input mt-1" required>
        <p class="text-xs text-gray-500 mt-1">Prefijo numérico (ej. 52, 521, 5255, 1, 349, etc.)</p>
        @error('codes')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Cost (interno)</label>
          <input type="number" step="0.0001" name="cost" value="{{ old('cost') }}" class="input mt-1">
          @error('cost')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Rate (MXN/min)</label>
          <input type="number" step="0.0001" name="rate" value="{{ old('rate') }}" class="input mt-1" required>
          @error('rate')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
      <div class="flex items-center gap-3">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
          <input type="checkbox" name="activo" value="1" class="rounded border-gray-300" {{ old('activo', true) ? 'checked' : '' }}>
          Activo
        </label>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.tarifas.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection 