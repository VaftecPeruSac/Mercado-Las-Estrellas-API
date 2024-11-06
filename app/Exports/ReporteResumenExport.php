<?php

namespace App\Exports;

use App\Models\DetallePagos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteResumenExport implements FromCollection, WithHeadings, WithStyles
{
  protected $id_puesto;

  public function __construct($id_puesto)
  {
    $this->id_puesto = $id_puesto;
  }

  public function collection()
  {
    return DetallePagos::with([
      'pago',
    ])
    ->where('id_puesto', $this->id_puesto)
    ->get()
    ->map(function ($detallePagos) {
      return [
        'serie_numero' => $detallePagos->pago ? $detallePagos->pago->serie.'-'.$detallePagos->pago->numero_pago : '-',
        'importe_ingreso' => $detallePagos->importe,
        'importe_gastos_administrativo' => 0,
        'importe_multas_inasistencia' => 0,
        'importe_pagos_transferencia' => 0,
        'importe_cuotas_extraordinarias' => 0,
        'importe_total' => $detallePagos->importe,
      ];
    });
  }

  public function headings(): array
  {
    return [
      'Serie y Número',
      'Importe Ingreso',
      'Importe Gastos Administrativo',
      'Importe Multas Inasistencia',
      'Importe Pagos Transferencia',
      'Importe Cuotas Extraordinarias',
      'Importe Total',
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