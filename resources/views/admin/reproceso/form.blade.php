@extends('layouts.app')

@section('title', 'Reprocesamiento')
@section('page-title', 'Reprocesamiento Manual de Llamadas')

@section('content')
<div class="max-w-3xl space-y-6">
  @if(session('status'))
    <div class="card p-3 whitespace-pre-wrap border border-blue-200 bg-blue-50 text-blue-800">{{ session('status') }}</div>
  @endif

  <div class="card p-6">
    <form action="{{ route('admin.reproceso.run') }}" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700">Desde</label>
        <input type="date" name="desde" class="input mt-1" required>
        @error('desde')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Hasta</label>
        <input type="date" name="hasta" class="input mt-1" required>
        @error('hasta')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Cliente (opcional)</label>
        <select name="cliente_id" class="input mt-1">
          <option value="">Todos</option>
          @foreach($clientes as $c)
            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
          <input type="checkbox" name="force" value="1" class="rounded border-gray-300">
          Forzar reprocesamiento aunque ya existan registros
        </label>
      </div>
      <div class="md:col-span-2 flex gap-3">
        <button class="btn btn-primary" type="submit">Ejecutar reproceso</button>
      </div>
    </form>
  </div>
</div>
@endsection 