<?php

namespace App\Exports;

use App\Models\Socio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SociosExport implements FromCollection, WithHeadings, WithStyles
{    /**
     * @return \Illuminate\Support\Collection
     */
    
    public function collection()
    {
        return Socio::with([
            'usuario.persona', // Carga la relación usuario y, dentro de usuario, la relación persona
            'puesto.block',    // Carga la relación puesto y, dentro de puesto, la relación block
            'puesto.gironegocio',     // Carga la relación puesto y, dentro de puesto, la relación giro
            'puesto.inquilino' // Carga la relación puesto y, dentro de puesto, la relación inquilino
        ])->get()->map(function($socio) {
            return [
                'nombre_usuario' => $socio->usuario->nombre_usuario ?? '------', 
                'dni' => $socio->usuario->persona->dni ?? '------', 
                'bloque' => $socio->puesto->block->nombre ?? '------', 
                'puesto' => $socio->puesto->numero_puesto ?? '------', 
                'giro' => $socio->puesto->gironegocio->nombre ?? '------', 
                'telefono' => $socio->usuario->persona->telefono ?? '------', 
                'correo' => $socio->usuario->persona->correo ?? '------', 
                'inquilino' => $socio->puesto->inquilino->nombre_completo ?? '------', 
                'fecha_registro' => $socio->fecha_registro ?? '------', 
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'DNI',
            'Block',
            'Puesto',
            'Giro Negocio',
            'Telefono',
            'Correo',
            'Inquilino',
            'Fecha registro',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

}
