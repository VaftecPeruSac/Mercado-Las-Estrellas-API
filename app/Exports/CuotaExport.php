<?php

namespace App\Exports;

use App\Models\Cuota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CuotaExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Cuota::with([
        ])->get()->map(function($cuota) {
            return [
                'id' => $cuota->id_cuota ?? '------', 
                'fecha_registro' => $cuota->fecha_registro ?? '------', 
                'fecha_vencimiento' => $cuota->fecha_vencimiento ?? '------', 
                'importe' => $cuota->importe ?? '------', 
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Fecha de Emision',
            'Fecha de Vencimiento',
            'Importe',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

}
