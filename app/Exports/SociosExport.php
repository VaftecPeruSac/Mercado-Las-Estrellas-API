<?php

namespace App\Exports;

use App\Models\Socio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SociosExport implements FromCollection, WithHeadings, WithStyles
{
    private $rowCount = 1; // Contador de filas para aplicar estilos dinámicos

    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $data = collect();

        Socio::with(['puestos.block', 'puestos.gironegocio', 'puestos.inquilino'])->get()->each(function ($socio) use ($data) {
            $socioData = [
                'nombre' => $socio->nombres . ' ' . $socio->apellido_paterno . ' ' . $socio->apellido_materno ?? '------',
                'dni' => $socio->dni ?? '------',
                'telefono' => $socio->telefono ?? '------',
                'correo' => $socio->correo ?? '------',
                'fecha_registro' => $socio->fecha_registro ?? '------',
            ];

            $rowStart = $this->rowCount + 1; // Guardamos el inicio de las filas para fusionar

            // Añadir una fila por cada puesto
            foreach ($socio->puestos as $puesto) {
                $data->push([
                    $socioData['nombre'], // Se llenará solo en la primera fila
                    $socioData['dni'],
                    $socioData['telefono'],
                    $socioData['correo'],
                    $puesto->block->nombre ?? '------',
                    $puesto->numero_puesto ?? '------',
                    $puesto->gironegocio->nombre ?? '------',
                    $puesto->inquilino->nombre.' '.$puesto->inquilino->apellido_paterno.' '.$puesto->inquilino->apellido_materno ?? '------',
                    $socioData['fecha_registro'],
                ]);

                // Vaciar datos para las subfilas
                $socioData = array_fill_keys(array_keys($socioData), '');
            }

            $this->rowCount = $rowStart + count($socio->puestos) - 1; // Actualizamos el contador
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'DNI',
            'Telefono',
            'Correo',
            'Block',
            'Puesto',
            'Giro Negocio',
            'Inquilino',
            'Fecha registro',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $row = 2; // Comenzar después de los encabezados

        foreach (Socio::withCount('puestos')->get() as $socio) {
            $rowStart = $row;
            $rowEnd = $rowStart + $socio->puestos_count - 1;

            if ($socio->puestos_count > 1) {
                // Fusionar celdas de las columnas principales
                $sheet->mergeCells("A{$rowStart}:A{$rowEnd}");
                $sheet->mergeCells("B{$rowStart}:B{$rowEnd}");
                $sheet->mergeCells("C{$rowStart}:C{$rowEnd}");
                $sheet->mergeCells("D{$rowStart}:D{$rowEnd}");
                $sheet->mergeCells("I{$rowStart}:I{$rowEnd}");

                // Centramos contenido vertical y horizontalmente
                $sheet->getStyle("A{$rowStart}:A{$rowEnd}")->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle("B{$rowStart}:B{$rowEnd}")->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle("C{$rowStart}:C{$rowEnd}")->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle("D{$rowStart}:D{$rowEnd}")->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle("I{$rowStart}:I{$rowEnd}")->getAlignment()->setHorizontal('center')->setVertical('center');
            }

            $row += $socio->puestos_count; // Avanzar a la siguiente sección
        }

        // Aplicar estilos generales
        $sheet->getStyle(1)->getFont()->setBold(true); // Encabezados en negrita
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true); // Ancho automático
        }
    }
}
