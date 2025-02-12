<?php

namespace App\Exports;

use App\Models\Pago;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportePagosExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $id_socio;
    private $count = 0;

    public function __construct($id_socio)
    {
        $this->id_socio = $id_socio;
    }

    public function collection()
    {
        $pagos = Pago::where('id_socio', $this->id_socio)
            ->get()
            ->map(function ($pago) {
                return [
                    'numero' => $pago->numero_pago,
                    'serie_numero' => $pago->serie.'-'.$pago->numero_pago,
                    'fecha' => $pago->fecha_registro,
                    'aporte' => $pago->total_pago,
                    'total' => $pago->total_pago,
                    'detalle_pagos' => $pago->detallePagos->map(function ($detalle) {
                            return $detalle->servicio->nombre . ': ' . $detalle->importe;
                        })->join('\n'), // Une los detalles en una sola cadena
                ];
            });

        $this->count = count($pagos);
        return $pagos;
    }

    public function headings(): array
    {
        return [
            'N° Pago',
            'N° Serie',
            'Fecha de Pago',
            'Aporte (S/.)',
            'Total (S/.)',
            'Detalle Pago',
        ];
    }

    public function columnFormats(): array
    {
        return[
            // 'D' => NumberFormat::FORMAT_DATE_DATETIME,
            // 'E' => NumberFormat::FORMAT_DATE_DATETIME
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // $sheet->getColumnDimension('D'.($this->count + 1))->setAutoSize(true);
        // $sheet->getStyle('B2')->getFont()->setBold(true);
        // $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $sheet->mergeCells('A'.($this->count + 2).':C'.($this->count + 2));
        // $event->sheet->getStyle('A:B')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.($this->count + 2))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('D'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('E'.($this->count + 2))->getFont()->setBold(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle(1)->getFont()->setBold(true);
                // $event->sheet->setCellValue('E'. ($event->sheet->getHighestRow()+1), '=SUM(E2:E'.$event->sheet->getHighestRow().')');
                $event->sheet->setCellValue('A'. ($lastRow), 'TOTAL:');
                $event->sheet->setCellValue('D'. ($lastRow), '=SUM(D2:D'.($lastRow-1).')');
                $event->sheet->setCellValue('E'. ($lastRow), '=SUM(E2:E'.($lastRow-1).')');
            }
        ];
    }
}