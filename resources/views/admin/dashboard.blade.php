@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Clientes -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-primary-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Clientes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_clientes'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Tarifas -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-green-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Tarifas Activas</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_tarifas'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total CDRs -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-yellow-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Llamadas</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_cdrs'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Llamadas Contestadas -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-purple-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Llamadas Contestadas</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['cdrs_answered'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido adicional -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Estado del sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del Sistema</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Base de datos</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Conectado
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Procesamiento CDRs</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Simulado
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tarifas cargadas</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $stats['total_tarifas'] }} activas
                    </span>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-primary w-full justify-center">
                    Gestionar Clientes
                </a>
                <a href="{{ route('admin.tarifas.index') }}" class="btn btn-secondary w-full justify-center">
                    Configurar Tarifas
                </a>
                <a href="{{ route('admin.llamadas.index') }}" class="btn btn-secondary w-full justify-center">
                    Ver Llamadas
                </a>
            </div>
        </div>
    </div>

    <!-- Información de la fase actual -->
    <div class="card p-6 bg-blue-50 border border-blue-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">FASE 1: Fundación y Estructura Base</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>✅ Laravel configurado con dependencias</p>
                    <p>✅ Migraciones y modelos creados</p>
                    <p>✅ Seeders con datos de prueba</p>
                    <p>✅ Layout base con diseño moderno</p>
                    <p>🔄 Dashboard funcional</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 