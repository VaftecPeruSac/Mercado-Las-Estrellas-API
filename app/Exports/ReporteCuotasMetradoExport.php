<?php

namespace App\Exports;

use App\Models\DetallePagos;
use App\Models\Deuda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCuotasMetradoExport implements FromCollection, WithHeadings, WithStyles
{
  protected $id_cuota;

  public function __construct($id_cuota)
  {
    $this->id_cuota = $id_cuota;
  }

  public function collection()
  {
    $id_cuota = $this->id_cuota;
    return  Deuda::whereExists(function ($query) use ($id_cuota) {
      $query->select("deuda_cuotas.id_deuda")
        ->from('deuda_cuotas')
        ->join('cuota_servicios','deuda_cuotas.id_cuota_servicio','cuota_servicios.id_cuota_servicio')
        ->whereRaw('deudas.id_deuda = deuda_cuotas.id_deuda')
        ->where('cuota_servicios.id_cuota', $id_cuota);
    })
    ->get()
    ->map(function ($deuda) use ($id_cuota) {

      $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
      $importe_pagado = $importeSuma ? $importeSuma : 0;

      return [
        'id_cuota' => $id_cuota,
        'nombre_completo' => $deuda->socio && $deuda->socio->persona ? $deuda->socio->persona->nombre_completo : '',
        'numero_puesto' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
        'area' => $deuda->puesto ? $deuda->puesto->area : '',
        'total' => $deuda->total_deuda,
        'importe_pagado' => $importe_pagado,
        'fecha' => $deuda->fecha_registro,
      ];

    });
  }

  public function headings(): array
  {
    return [
      'ID Cuota',
      'Nombre Completo',
      'Numero Puesto',
      'Area',
      'Total',
      'Importe Pagado',
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