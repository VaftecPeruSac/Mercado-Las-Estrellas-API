<?php

namespace App\Exports;

use App\Models\Pago;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PagosExport implements  FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pago::with([
            'socio.puesto',
            'socio.usuario.persona',
            'socio.deuda.cuotas', // Relación correcta en plural
            'socio.puesto',
        ])->get()->map(function($pago) {
            $a_cuenta = '------';
    
            // Verificar si hay deudas y cuotas relacionadas
            if ($pago->socio->deuda && $pago->socio->deuda->cuotas->isNotEmpty()) {
                // Iterar sobre las cuotas relacionadas para acceder a los valores de la tabla pivote
                $cuotas = $pago->socio->deuda->cuotas;
                foreach ($cuotas as $cuota) {
                    $a_cuenta = $cuota->pivot->a_cuenta ?? '------'; // Acceder al campo 'a_cuenta' de la tabla pivote
                    break; // Si solo te interesa el primer valor de 'a_cuenta', puedes salir del bucle aquí
                }
            }
    
            return [
                'id' => $pago->id_pago?? '------', 
                'n_puesto' => $pago->socio->puesto->numero_puesto ?? '------', 
                'socio' => $pago->socio->usuario->nombre_usuario ?? '------', 
                'dni' => $pago->socio->usuario->persona->dni ?? '------', 
                'fecha_registro' => $pago->fecha_registro ?? '------', 
                'telefono' => $pago->socio->usuario->persona->telefono ?? '------', 
                'correo' => $pago->socio->usuario->persona->correo ?? '------', 
                'a_cuenta' => $a_cuenta,
                'monto_total' => $pago->total_pago ?? '------', 
            ];
        });
    }
    
    
    public function headings(): array
    {
        return [
            'ID',
            'Nro. Puesto',
            'Socio',
            'DNI',
            'fecha_registro',
            'Telefono',
            'Correo',
            'A cuenta',
            'Monto Actual',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle(1)->getFont()->setBold(true);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

}
