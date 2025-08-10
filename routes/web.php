<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TarifaDestinoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\LlamadaController;
use App\Http\Controllers\Admin\ClienteDashboardController;
use App\Http\Controllers\Admin\DestinoDesconocidoController;
use App\Http\Controllers\Admin\ReprocesamientoController;
use App\Http\Controllers\Admin\ClienteTarifaEspecialController;
use App\Http\Controllers\Admin\FacturaController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes (Sin autenticación por ahora)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Facturas
    Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('/facturas/{factura}/export-resumen', [FacturaController::class, 'exportResumen'])->name('facturas.export.resumen');
    Route::get('/facturas/{factura}/export-detalle', [FacturaController::class, 'exportDetalle'])->name('facturas.export.detalle');

    // Atajo para generar facturas (opcional)
    Route::post('/facturas/generar', function() {
        $año = request('año', now()->year);
        $mes = request('mes', now()->month);
        $clienteId = request('cliente_id');
        $params = ['--año' => $año, '--mes' => $mes];
        if ($clienteId) $params['--cliente_id'] = $clienteId;
        Artisan::call('facturas:generar', $params);
        return back()->with('status', "Generación ejecutada:\n".Artisan::output());
    })->name('facturas.generar');

    // Dashboard de cliente y exportaciones
    Route::get('/clientes/{cliente}/dashboard', [ClienteDashboardController::class, 'dashboard'])->name('cliente.dashboard');
    Route::get('/clientes/{cliente}/export-detalle', [ClienteDashboardController::class, 'exportDetalle'])->name('cliente.export.detalle');
    Route::get('/clientes/{cliente}/export-resumen', [ClienteDashboardController::class, 'exportPeriodo'])->name('cliente.export.periodo');

    // Clientes CRUD
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    // CLIDs management
    Route::post('/clientes/{cliente}/clids', [ClienteController::class, 'addClid'])->name('clientes.clids.add');
    Route::put('/clientes/{cliente}/clids/{clid}/toggle', [ClienteController::class, 'toggleClid'])->name('clientes.clids.toggle');
    Route::delete('/clientes/{cliente}/clids/{clid}', [ClienteController::class, 'deleteClid'])->name('clientes.clids.delete');

    // Tarifas especiales por cliente
    Route::get('/clientes/{cliente}/especiales', [ClienteTarifaEspecialController::class, 'index'])->name('clientes.especiales.index');
    Route::post('/clientes/{cliente}/especiales', [ClienteTarifaEspecialController::class, 'store'])->name('clientes.especiales.store');
    Route::put('/clientes/{cliente}/especiales/{especial}/toggle', [ClienteTarifaEspecialController::class, 'toggle'])->name('clientes.especiales.toggle');
    Route::delete('/clientes/{cliente}/especiales/{especial}', [ClienteTarifaEspecialController::class, 'destroy'])->name('clientes.especiales.destroy');

    // Tarifas CRUD
    Route::get('/tarifas', [TarifaDestinoController::class, 'index'])->name('tarifas.index');
    Route::get('/tarifas/create', [TarifaDestinoController::class, 'create'])->name('tarifas.create');
    Route::post('/tarifas', [TarifaDestinoController::class, 'store'])->name('tarifas.store');
    Route::get('/tarifas/{tarifa}/edit', [TarifaDestinoController::class, 'edit'])->name('tarifas.edit');
    Route::put('/tarifas/{tarifa}', [TarifaDestinoController::class, 'update'])->name('tarifas.update');
    Route::delete('/tarifas/{tarifa}', [TarifaDestinoController::class, 'destroy'])->name('tarifas.destroy');

    // Llamadas
    Route::get('/llamadas', [LlamadaController::class, 'index'])->name('llamadas.index');

    // Destinos desconocidos
    Route::get('/destinos', [DestinoDesconocidoController::class, 'index'])->name('destinos.index');
    Route::put('/destinos/{destino}/notificar', [DestinoDesconocidoController::class, 'marcarNotificado'])->name('destinos.notificar');
    Route::put('/destinos/{destino}/procesado', [DestinoDesconocidoController::class, 'marcarProcesado'])->name('destinos.procesado');
    Route::post('/destinos/{destino}/crear-tarifa', [DestinoDesconocidoController::class, 'crearTarifa'])->name('destinos.crear-tarifa');

    // Reprocesamiento manual
    Route::get('/reproceso', [ReprocesamientoController::class, 'form'])->name('reproceso.form');
    Route::post('/reproceso', [ReprocesamientoController::class, 'run'])->name('reproceso.run');
});
