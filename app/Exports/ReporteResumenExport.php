<?php

namespace App\Exports;

use App\Models\DetallePagos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteResumenExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $id_puesto;
    private $count = 0;

    public function __construct($id_puesto)
    {
        $this->id_puesto = $id_puesto;
    }

    public function collection()
    {
        $detalles = DetallePagos::with([
                'pago',
            ])
            ->where('id_puesto', $this->id_puesto)
            ->get()
            ->map(function ($detallePagos) {
                return [
                    'serie_numero' => $detallePagos->pago ? $detallePagos->pago->serie.'-'.$detallePagos->pago->numero_pago : '-',
                    'importe_ingreso' => $detallePagos->importe,
                    'importe_gastos_administrativo' => 0,
                    'importe_multas_inasistencia' => 0,
                    'importe_pagos_transferencia' => 0,
                    'importe_cuotas_extraordinarias' => 0,
                    'importe_total' => $detallePagos->importe,
                ];
            });

        $this->count = count($detalles);
        return $detalles;
    }

    public function headings(): array
    {
        return [
            'Nro. Pago',
            'Imp. Ingreso',
            'Imp. Gastos Administrativo',
            'Imp. Multas Inasistencia',
            'Imp. Pagos Transferencia',
            'Imp. Cuotas Extraordinarias',
            'Imp. Total',
        ];
    }

    public function columnFormats(): array
    {
        return[
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getStyle('A'.($this->count + 2))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('B'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('C'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('D'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('E'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('F'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('G'.($this->count + 2))->getFont()->setBold(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle(1)->getFont()->setBold(true);
                $event->sheet->setCellValue('A'. ($lastRow), 'TOTAL:');
                $event->sheet->setCellValue('B'. ($lastRow), '=SUM(B2:B'.($lastRow-1).')');
                $event->sheet->setCellValue('C'. ($lastRow), '=SUM(C2:C'.($lastRow-1).')');
                $event->sheet->setCellValue('D'. ($lastRow), '=SUM(D2:D'.($lastRow-1).')');
                $event->sheet->setCellValue('E'. ($lastRow), '=SUM(E2:E'.($lastRow-1).')');
                $event->sheet->setCellValue('F'. ($lastRow), '=SUM(F2:F'.($lastRow-1).')');
                $event->sheet->setCellValue('G'. ($lastRow), '=SUM(G2:G'.($lastRow-1).')');
            }
        ];
    }
}