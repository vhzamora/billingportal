<?php

namespace App\Exports;

use App\Models\LlamadaFacturada;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResumenPeriodoClienteExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $clienteId,
        protected CarbonInterface $desde,
        protected CarbonInterface $hasta
    ) {}

    public function collection()
    {
        $base = LlamadaFacturada::where('cliente_id', $this->clienteId)
            ->whereBetween('fecha_llamada', [$this->desde, $this->hasta]);

        $total = (clone $base)->selectRaw('COUNT(*) as llamadas, SUM(minutos_facturados) as minutos, SUM(costo_cliente) as importe')->first();
        $byDestino = (clone $base)
            ->selectRaw('COALESCE(destination_encontrado, "Desconocido") as destino, COUNT(*) as llamadas, SUM(minutos_facturados) as minutos, SUM(costo_cliente) as importe')
            ->groupBy('destino')
            ->orderByDesc('importe')
            ->get();

        $rows = new Collection();
        $rows->push(['Resumen del período', '', '', '']);
        $rows->push(['Llamadas', 'Minutos', 'Importe', '']);
        $rows->push([(int)$total->llamadas, (int)$total->minutos, (float)$total->importe, '']);
        $rows->push(['', '', '', '']);
        $rows->push(['Desglose por destino', '', '', '']);
        $rows->push(['Destino', 'Llamadas', 'Minutos', 'Importe']);
        foreach ($byDestino as $row) {
            $rows->push([$row->destino, (int)$row->llamadas, (int)$row->minutos, (float)$row->importe]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Col1', 'Col2', 'Col3', 'Col4'];
    }
} 