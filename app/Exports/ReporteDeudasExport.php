<?php

namespace App\Exports;

use App\Models\DetallePagos;
use App\Models\Deuda;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteDeudasExport implements FromCollection, WithHeadings, WithStyles
{
  protected $id_puesto;

  public function __construct($id_puesto)
  {
    $this->id_puesto = $id_puesto;
  }

  public function collection()
  {
    return Deuda::with([
      'servicio',
    ])
    ->where('id_puesto', $this->id_puesto)
    ->get()
    ->map(function ($deuda) {

      $mes = '';
      $mesCarbon = (new Carbon( $deuda->fecha_registro ))->format('m');
      
      switch($mesCarbon) {
        case '01': $mes = 'Enero';
          break;
        case '02': $mes = 'Febreo';
          break;
        case '03': $mes = 'Marzo';
          break;
        case '04': $mes = 'Marzo';
          break;
        case '05': $mes = 'Abril';
          break;
        case '06': $mes = 'Mayo';
          break;
        case '07': $mes = 'Junio';
          break;
        case '08': $mes = 'Julio';
          break;
        case '09': $mes = 'Agosto';
          break;
        case '10': $mes = 'Septiembre';
          break;
        case '11': $mes = 'Noviembre';
          break;
        case '12': $mes = 'Diciembre';
          break;
      }

      $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
      $importe_pagado = $importeSuma ?? 0;
      $importe_por_pagar = $deuda->total_deuda;

      return [
        'id_cuota' => $deuda->id_cuota,
        'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
        'mes' => $mes,
        'servicio_descripcion' => $deuda->servicio->descripcion ?? '-----',
        'total' => $deuda->total_deuda ?? 0,
        'importe_pagado' => $importe_pagado ?? 0,
        'importe_por_pagar' => $importe_por_pagar ?? 0,
      ];
    });
  }

  public function headings(): array
  {
    return [
      'ID Cuota',
      'Año',
      'Mes',
      'Desc. Servicios por Cuota',
      'Total (S/.)',
      'Imp. Pagado (S/.)',
      'Imp. Por pagar (S/.)',
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