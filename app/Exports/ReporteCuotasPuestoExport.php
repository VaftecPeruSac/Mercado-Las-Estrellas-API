<?php

namespace App\Exports;

use App\Models\DetallePagos;
use App\Models\Deuda;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCuotasPuestoExport implements FromCollection, WithHeadings, WithStyles
{
  protected $id_puesto;

  public function __construct($id_puesto){
    $this->id_puesto = $id_puesto;
  }

  public function collection()
  {
    return Deuda::with([
      'servicio'
    ])
    ->where('id_puesto', $this->id_puesto)
    ->get()
    ->map(function ($deuda) {

      $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
      $importe_pagado = $importeSuma ? $importeSuma : 0;
      $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

      return [
        'id_cuota' => $deuda->id_cuota,
        'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
        'servicio_descripcion' => $deuda->servicio ? $deuda->servicio->descripcion : '',
        'aprobado' => $deuda->total_deuda,
        'pagado' => $importe_pagado,
        'por_pagar' => $importe_por_pagar,
        'fecha' => $deuda->fecha_registro,
      ];
    });
  }

  public function headings(): array
  {
    return [
      'ID Cuota',
      'Año',
      'Servicio',
      'Aprobado',
      'Pagado',
      'Por Pagar',
      'Fecha'
    ];
  }

  public function styles(Worksheet $sheet)
  {
    // Aplicar negrita a la primera fila (encabezados)
    $sheet->getStyle(1)->getFont()->setBold(true);

    // Ajustar automáticamente el ancho de las columnas
    foreach (range('A', 'G') as $column) {
      $sheet->getColumnDimension($column)->setAutoSize(true);
    }
  }
}