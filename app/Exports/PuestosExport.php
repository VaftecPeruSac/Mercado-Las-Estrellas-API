<?php

namespace App\Exports;

use App\Models\Puesto;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PuestosExport implements FromCollection, WithHeadings, WithColumnWidths,WithColumnFormatting, WithStyles
{    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Puesto::with([
            'socio.usuario',
            'block',
            'gironegocio',
            'inquilino' 
        ])->get()->map(function($puesto) {
            return [
                'bloque' => $puesto->block->nombre ?? '------', 
                'puesto' => $puesto->numero_puesto ?? '------', 
                'area' => $puesto->area ?? '------', 
                'giro' => $puesto->gironegocio->nombre ?? '------', 
                'socio' => $puesto->socio->usuario->nombre_usuario ?? '------', 
                'inquilino' => $puesto->inquilino->nombre_completo ?? '------', 
                'estado' => $puesto->estado ?? '------', 
                'fecha_registro' => $fecha_registro ?? '------', 
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'Bloque',
            'Puesto',
            'Area',
            'Giro Negocio',
            'Socio',
            'Inquilino',
            'estado',
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
            'F' => 13,
            'G' => 25,
            'H' => 25,
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

