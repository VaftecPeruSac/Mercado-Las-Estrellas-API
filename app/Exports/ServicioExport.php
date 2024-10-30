<?php

namespace App\Exports;

use App\Models\Servicio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServicioExport implements FromCollection, WithHeadings, WithStyles
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
                'tipo_servicio' => $servicio->tipo_servicio === 3 ? 'Servicio por metros cuadrados' : ($servicio->tipo_servicio === 2 ? 'Extraordinario' : 'Ordinario'),
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

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

}
