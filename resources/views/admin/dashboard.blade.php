@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="space-y-6">
    <!-- KPIs -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Clientes activos -->
        <div class="card overflow-hidden">
            <div class="px-4 py-3 bg-blue-600 text-white flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wider uppercase">Clientes activos</span>
                <svg class="h-5 w-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2h8zm1-10a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_clientes'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Clientes con CLIDs activos</div>
            </div>
        </div>
        <!-- Tarifas activas -->
        <div class="card overflow-hidden">
            <div class="px-4 py-3 bg-green-600 text-white flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wider uppercase">Tarifas activas</span>
                <svg class="h-5 w-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_tarifas'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Destinos disponibles para rating</div>
            </div>
        </div>
        <!-- CDRs totales -->
        <div class="card overflow-hidden">
            <div class="px-4 py-3 bg-amber-500 text-white flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wider uppercase">CDRs totales</span>
                <svg class="h-5 w-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-5m-2 0H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H9m11-6H4" />
                </svg>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_cdrs'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Registros totales en el período</div>
            </div>
        </div>
        <!-- CDRs contestados -->
        <div class="card overflow-hidden">
            <div class="px-4 py-3 bg-purple-600 text-white flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wider uppercase">CDRs contestados</span>
                <svg class="h-5 w-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['cdrs_answered'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Llamadas con conversación</div>
            </div>
        </div>
    </div>

    <!-- Gráficas: mitad izquierda (Facturación/Utilidad) y mitad derecha (Llamadas vs Completadas) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6 w-full">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2 w-full">
                <h3 class="text-lg font-medium text-gray-900">Facturación y Utilidad del mes</h3>
                <div class="text-sm text-gray-500">{{ $inicioMes->format('M d') }} - {{ $finMes->format('M d') }}</div>
            </div>
            <div class="text-sm text-gray-600 mb-3 space-x-6 w-full">
                <span>Total facturación: $ {{ number_format($totalMesImporte, 2) }}</span>
                <span>Total utilidad: $ {{ number_format($totalMesUtilidad, 2) }}</span>
            </div>
            <div class="h-80 w-full">
                <canvas id="chartFacturacionUtilidad" class="block w-full" style="width:100%"></canvas>
            </div>
        </div>
        <div class="card p-6 w-full">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2 w-full">
                <h3 class="text-lg font-medium text-gray-900">Llamadas realizadas vs completadas</h3>
                <div class="text-sm text-gray-500">{{ $inicioMes->format('M d') }} - {{ $finMes->format('M d') }}</div>
            </div>
            <div class="h-80 w-full">
                <canvas id="chartLlamadas" class="block w-full" style="width:100%"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Ten -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Top 10 Destinos (por facturación)</h3>
                <div class="text-sm text-gray-500">{{ $inicioMes->format('M d') }} - {{ $finMes->format('M d') }}</div>
            </div>
            @php
                $totalDestinosImporte = max(1, (float) $topDestinos->sum('importe'));
                $flagMap = [
                    'México' => 'mx', 'Mexico' => 'mx',
                    'España' => 'es', 'Spain' => 'es',
                    'Estados Unidos' => 'us', 'USA' => 'us', 'EEUU' => 'us',
                    'Canadá' => 'ca', 'Canada' => 'ca',
                    'Reino Unido' => 'gb', 'United Kingdom' => 'gb', 'UK' => 'gb',
                    'Francia' => 'fr', 'France' => 'fr',
                    'Alemania' => 'de', 'Germany' => 'de',
                    'Argentina' => 'ar',
                    'Colombia' => 'co',
                    'Chile' => 'cl',
                    'Perú' => 'pe', 'Peru' => 'pe',
                    'Brasil' => 'br', 'Brazil' => 'br',
                    'Italia' => 'it', 'Italy' => 'it',
                    'Japón' => 'jp', 'Japan' => 'jp',
                ];
            @endphp
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Destino</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="sr-only">Llamadas</span>
                                <svg class="inline-block h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="sr-only">Minutos</span>
                                <svg class="inline-block h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="sr-only">Importe</span>
                                <span class="inline-block text-gray-600">$</span>
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="sr-only">% participación</span>
                                <span class="inline-block text-gray-600">%</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($topDestinos as $d)
                            @php($pct = round(((float)$d->importe / $totalDestinosImporte) * 100, 2))
                            @php($iso = null)
                            @foreach($flagMap as $name=>$code)
                                @if(Str::contains($d->destino, $name))
                                    @php($iso = $code)
                                    @break
                                @endif
                            @endforeach
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        @if($iso)
                                            <img src="https://flagcdn.com/24x18/{{ $iso }}.png" alt="{{ $iso }}" class="h-3 w-auto rounded-sm border border-gray-200" />
                                        @else
                                            <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0c2.5 2.5 2.5 7.5 0 10M12 2c-2.5 2.5-2.5 7.5 0 10M2 12h20" />
                                            </svg>
                                        @endif
                                        <span class="font-medium text-gray-900 truncate">{{ $d->destino }}</span>
                                    </div>
                                    <div class="mt-2 h-1.5 bg-gray-100 rounded">
                                        <div class="h-1.5 rounded bg-blue-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ (int)$d->llamadas }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ (int)$d->minutos }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">$ {{ number_format($d->importe, 2) }}</td>
                                <td class="px-4 py-3 text-right"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $pct }}%</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Sin datos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Top 10 Clientes (dos secciones) -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Top 10 Clientes</h3>
                <div class="text-sm text-gray-500">{{ $inicioMes->format('M d') }} - {{ $finMes->format('M d') }}</div>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-semibold text-gray-800">Facturación</div>
                    </div>
                    <div class="h-64">
                        <canvas id="chartTopClientesFact"></canvas>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-semibold text-gray-800">Tráfico (minutos)</div>
                    </div>
                    <div class="h-64">
                        <canvas id="chartTopClientesMin"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-primary w-full justify-center">Gestionar Clientes</a>
                <a href="{{ route('admin.tarifas.index') }}" class="btn btn-secondary w-full justify-center">Configurar Tarifas</a>
                <a href="{{ route('admin.llamadas.index') }}" class="btn btn-secondary w-full justify-center">Ver Llamadas</a>
                <form action="{{ route('admin.facturas.generar') }}" method="post" class="w-full">
                    @csrf
                    <input type="hidden" name="año" value="{{ now()->year }}">
                    <input type="hidden" name="mes" value="{{ now()->format('n') }}">
                    <button type="submit" class="btn btn-primary w-full justify-center">Generar facturas del mes actual</button>
                </form>
                <a href="{{ route('admin.facturas.index') }}?año={{ now()->year }}&mes={{ now()->format('n') }}" class="btn btn-secondary w-full justify-center">Ver facturas del mes actual</a>
            </div>
        </div>
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del Sistema</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Base de datos</span><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Conectado</span></div>
                <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Procesamiento CDRs</span><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Simulado</span></div>
                <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Tarifas cargadas</span><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $stats['total_tarifas'] }} activas</span></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
(function(){
  const facturacion = @json($facturacionSeries);
  const utilidad = @json($utilidadSeries);
  const ctx = document.getElementById('chartFacturacionUtilidad');
  if (ctx) new Chart(ctx, { type: 'line', data: { datasets: [ { label: 'Facturación', data: facturacion, borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.12)', tension: .25, fill: true }, { label: 'Utilidad', data: utilidad, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.12)', tension: .25, fill: true } ] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, stacked: false, scales: { x: { type: 'time', time: { unit: 'day' } }, y: { beginAtZero: true } }, plugins: { legend: { display: true } } } });

  const llamadas = @json($llamadasSeries);
  const completadas = @json($completadasSeries);
  const ctxL = document.getElementById('chartLlamadas');
  if (ctxL) new Chart(ctxL, { type: 'bar', data: { datasets: [ { label: 'Realizadas', data: llamadas, backgroundColor: 'rgba(99,102,241,0.5)', borderColor: '#6366f1' }, { label: 'Completadas', data: completadas, backgroundColor: 'rgba(34,197,94,0.5)', borderColor: '#22c55e' } ] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, scales: { x: { type: 'time', time: { unit: 'day' } }, y: { beginAtZero: true } } } });

  const labelsFact = @json($topClientesFactLabels);
  const dataFact = @json($topClientesFactImporte);
  const labelsMin = @json($topClientesMinLabels);
  const dataMin = @json($topClientesMinMinutos);

  // Paleta compartida para asegurar el mismo orden de colores en ambas gráficas
  const sharedPalette = ['#2563eb','#16a34a','#f59e0b','#dc2626','#7c3aed','#059669','#d97706','#4f46e5','#ea580c','#0ea5e9'];

  const ctxPieFact = document.getElementById('chartTopClientesFact');
  if (ctxPieFact) new Chart(ctxPieFact, {
    type: 'pie',
    data: {
      labels: labelsFact,
      datasets: [{ data: dataFact, backgroundColor: sharedPalette.slice(0, labelsFact.length) }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });

  const ctxPieMin = document.getElementById('chartTopClientesMin');
  if (ctxPieMin) new Chart(ctxPieMin, {
    type: 'pie',
    data: {
      labels: labelsMin,
      datasets: [{ data: dataMin, backgroundColor: sharedPalette.slice(0, labelsMin.length) }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });
})();
</script>
@endpush
@endsection 