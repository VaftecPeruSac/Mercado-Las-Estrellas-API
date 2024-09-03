<?php

namespace App\Exports;

use App\Models\Socio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SociosExport implements FromCollection, WithHeadings, WithColumnWidths,WithColumnFormatting
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
            'Nombre Usuario',
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
            'I' => 25,
            'J' => 25,
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
