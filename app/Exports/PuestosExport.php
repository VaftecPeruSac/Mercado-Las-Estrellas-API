<?php

namespace App\Exports;

use App\Models\Puesto;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PuestosExport implements FromCollection, WithHeadings, WithStyles
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
                'estado' => $puesto->estado === '1' ?  'Libre' : 'Ocupado',
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
            'Estado',
            'Fecha registro',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

}

