<?php

namespace App\Exports;

use App\Models\Servicio;
use App\Models\Socio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServicioExport implements FromCollection, WithHeadings, WithColumnWidths,WithColumnFormatting,WithStyles
{    /**
     * @return \Illuminate\Support\Collection
     */
    
    public function collection()
    {
        return Servicio::with([
        ])->get()->map(function($servicio) {
            return [
                'id' => $servicio->id_servicio ?? '------', 
                'descripcion' => $servicio->descripcion?? '------', 
                'costo_unitario' => $servicio->costo_unitario ?? '------', 
                'tipo_servicio' => $servicio->tipo_servicio ?? '------', 
                'fecha_registro' => $servicio->fecha_registro ?? '------', 
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Descripcion',
            'Costo Unitario',
            'Tipo Servicio',
            'Fecha registro',
        ];
    }
    public function columnWidths(): array
    {
        // Define el ancho de las columnas. Ajusta los valores según necesites.
        return [
            'A' => 20,
            'B' => 15,
            'C' => 13,
            'D' => 15,
            'E' => 25,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Aplica negrita a la primera fila (encabezados)
            1 => ['font' => ['bold' => true]],
        ];
    }
    public function columnFormats(): array
    {
        // Si deseas un formato específico para las columnas, por ejemplo, fechas
        return [
            // 'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
