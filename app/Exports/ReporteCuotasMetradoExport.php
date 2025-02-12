<?php

namespace App\Exports;

use App\Models\DetallePagos;
use App\Models\Deuda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteCuotasMetradoExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $id_cuota;

    public function __construct($id_cuota)
    {
        $this->id_cuota = $id_cuota;
    }

    public function collection()
    {
        $id_cuota = $this->id_cuota;
        $deudas = Deuda::whereExists(function ($query) use ($id_cuota) {
                $query->select("deuda_cuotas.id_deuda")
                    ->from('deuda_cuotas')
                    ->join('cuota_servicios','deuda_cuotas.id_cuota_servicio','cuota_servicios.id_cuota_servicio')
                    ->whereRaw('deudas.id_deuda = deuda_cuotas.id_deuda')
                    ->where('cuota_servicios.id_cuota', $id_cuota);
            })
            ->get()
            ->map(function ($deuda) use ($id_cuota) {

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ? $importeSuma : 0;

                return [
                    'id_cuota' => $id_cuota,
                    'nombre_completo' => $deuda->socio && $deuda->socio->persona ? $deuda->socio->persona->nombre_completo : '',
                    'numero_puesto' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
                    'area' => $deuda->puesto ? $deuda->puesto->area : '',
                    'total' => $deuda->total_deuda,
                    'importe_pagado' => $importe_pagado,
                    'fecha' => $deuda->fecha_registro,
                ];

            });

        $this->count = count($deudas);
        return $deudas;
    }

    public function headings(): array
    {
        return [
            'ID Cuota',
            'Nombre Completo',
            'Numero Puesto',
            'Area',
            'Total',
            'Importe Pagado',
            'Fecha'
        ];
    }

    public function columnFormats(): array
    {
        return[
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00
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

        $sheet->mergeCells('A'.($this->count + 2).':D'.($this->count + 2));
        $sheet->getStyle('A'.($this->count + 2))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('E'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('F'.($this->count + 2))->getFont()->setBold(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle(1)->getFont()->setBold(true);
                $event->sheet->setCellValue('A'. ($lastRow), 'TOTAL:');
                $event->sheet->setCellValue('E'. ($lastRow), '=SUM(E2:E'.($lastRow-1).')');
                $event->sheet->setCellValue('F'. ($lastRow), '=SUM(F2:F'.($lastRow-1).')');
            }
        ];
    }
}