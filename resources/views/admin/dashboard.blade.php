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

    <!-- Facturación y Utilidad del mes (combinado) -->
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

    <!-- Top Ten -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top 10 Destinos (por facturación)</h3>
            <div class="space-y-2">
                @foreach($topDestinos as $d)
                    <div class="flex items-center justify-between text-sm">
                        <div class="truncate pr-2">{{ $d->destino }}</div>
                        <div class="text-gray-600">{{ (int)$d->llamadas }} llamadas · {{ (int)$d->minutos }} min · $ {{ number_format($d->importe,2) }}</div>
                    </div>
                @endforeach
                @if($topDestinos->isEmpty())
                    <div class="text-sm text-gray-500">Sin datos.</div>
                @endif
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Top 10 Clientes (por facturación)</h3>
                <div class="text-sm text-gray-500">Participación %</div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="h-64">
                    <canvas id="chartTopClientes" class="block w-full" style="width:100%"></canvas>
                </div>
                <div class="space-y-2">
                    @foreach($topClientes as $c)
                        <div class="flex items-center justify-between text-sm">
                            <div class="truncate pr-2">{{ $c->cliente_nombre ?? ('Cliente #'.$c->cliente_id) }}</div>
                            <div class="text-gray-600">$ {{ number_format($c->importe,2) }}</div>
                        </div>
                    @endforeach
                    @if($topClientes->isEmpty())
                        <div class="text-sm text-gray-500">Sin datos.</div>
                    @endif
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

  const pieLabels = @json($topClientesLabels);
  const pieData = @json($topClientesImporte);
  const ctxPie = document.getElementById('chartTopClientes');
  if (ctxPie) new Chart(ctxPie, { type: 'pie', data: { labels: pieLabels, datasets: [{ data: pieData, backgroundColor: ['#2563eb','#16a34a','#f59e0b','#dc2626','#7c3aed','#059669','#d97706','#4f46e5','#ea580c','#0ea5e9'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
})();
</script>
@endpush
@endsection 