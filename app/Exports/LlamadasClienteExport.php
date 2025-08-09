<?php

namespace App\Exports;

use App\Models\LlamadaFacturada;
use Carbon\CarbonInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LlamadasClienteExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected int $clienteId,
        protected CarbonInterface $desde,
        protected CarbonInterface $hasta
    ) {}

    public function collection()
    {
        return LlamadaFacturada::where('cliente_id', $this->clienteId)
            ->whereBetween('fecha_llamada', [$this->desde, $this->hasta])
            ->orderBy('fecha_llamada')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Fecha', 'CLID', 'Origen', 'Destino', 'Segundos', 'Minutos', 'Tarifa', 'Importe', 'Destino Detectado'
        ];
    }

    public function map($l): array
    {
        return [
            $l->fecha_llamada->format('Y-m-d H:i'),
            $l->clid,
            $l->numero_origen,
            $l->numero_destino,
            $l->duracion_segundos,
            $l->minutos_facturados,
            number_format($l->tarifa_aplicada, 4),
            number_format($l->costo_cliente, 4),
            $l->es_destino_desconocido ? 'Desconocido' : ($l->destination_encontrado ?? ''),
        ];
    }
} 