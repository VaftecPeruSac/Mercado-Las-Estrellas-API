<?php

namespace App\Exports;

use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\DeudaCuota;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteCuotasPuestoExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $id_puesto;
    private $count = 0;

    public function __construct($id_puesto){
        $this->id_puesto = $id_puesto;
    }

    public function collection()
    {
        $deudas = Deuda::where('id_puesto', $this->id_puesto)
            ->get()
            ->map(function ($deuda) {
                $deudaCuotas = DeudaCuota::select('c.nombre')
                    ->join('cuota_servicios as b','deuda_cuotas.id_cuota_servicio','b.id_cuota_servicio')
                    ->join('servicios as c','b.id_servicio','c.id_servicio')
                    ->where('deuda_cuotas.id_deuda',$deuda->id_deuda)
                    ->groupBy('c.nombre')->get();
                $servicio_nombres = implode(', ', $deudaCuotas->pluck('nombre')->toArray());

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ? $importeSuma : 0;
                $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

                return [
                    'id_cuota' => '',
                    'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
                    'servicio_descripcion' => $servicio_nombres,
                    'aprobado' => $deuda->total_deuda,
                    'pagado' => $importe_pagado,
                    'por_pagar' => $importe_por_pagar,
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
            'Año',
            'Servicio',
            'Aprobado',
            'Pagado',
            'Por Pagar',
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

        $sheet->mergeCells('A'.($this->count + 2).':C'.($this->count + 2));
        $sheet->getStyle('A'.($this->count + 2))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.($this->count + 2))->getFont()->setBold(true);
        $sheet->getStyle('D'.($this->count + 2))->getFont()->setBold(true);
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
                $event->sheet->setCellValue('D'. ($lastRow), '=SUM(D2:D'.($lastRow-1).')');
                $event->sheet->setCellValue('E'. ($lastRow), '=SUM(E2:E'.($lastRow-1).')');
                $event->sheet->setCellValue('F'. ($lastRow), '=SUM(F2:F'.($lastRow-1).')');
            }
        ];
    }
}