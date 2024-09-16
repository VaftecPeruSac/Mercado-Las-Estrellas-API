<?php

namespace App\Exports;

use App\Models\Cuota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CuotaExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
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
            'Id',
            'fecha_registro',
            'fecha_vencimiento',
            'importe',
        ];
    }
    public function columnWidths(): array
    {
        // Define el ancho de las columnas. Ajusta los valores según necesites.
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 8,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Aplica negrita a la primera fila (encabezados)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
