<?php

namespace App\Exports;

use App\Models\Pago;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportePagosExport implements FromCollection, WithHeadings, WithStyles
{
  protected $id_socio;

  public function __construct($id_socio)
  {
    $this->id_socio = $id_socio;
  }

  public function collection()
  {
    return Pago::with([
      'detallePagos.deuda.servicio',
    ])
    ->where('id_socio', $this->id_socio)
    ->get()
    ->map(function ($pago) {
      return [
        'numero' => $pago->numero_pago,
        'serie_numero' => $pago->serie.'-'.$pago->numero_pago,
        'fecha' => $pago->fecha_registro,
        'aporte' => $pago->total_pago,
        'total' => $pago->total_pago,
        'detalle_pagos' => $pago->detallePagos->map(function ($detalle) {
          return $detalle->deuda->servicio->descripcion . ': ' . $detalle->importe;
        })->join('\n'), // Une los detalles en una sola cadena
      ];
    });
  }

  public function headings(): array
  {
    return [
      'N° Pago',
      'N° Serie',
      'Fecha de Pago',
      'Aporte (S/.)',
      'Total (S/.)',
      'Detalle Pago',
    ];
  }

  public function styles(Worksheet $sheet)
  {
    // Aplicar negrita a la primera fila (encabezados)
    $sheet->getStyle(1)->getFont()->setBold(true);

    // Ajustar automáticamente el ancho de las columnas
    foreach (range('A', 'F') as $column) {
      $sheet->getColumnDimension($column)->setAutoSize(true);
    }
  }
}