<?php

namespace App\Exports\PDF;

use App\Models\Cuota;
use App\Models\DetallePagos;
use App\Models\Deuda;
use Barryvdh\DomPDF\PDF;

class ReporteCuotasMetradoPDFExport {

  public function generatePDF($id_cuota) {

    $cuota = Cuota::find($id_cuota);
    $fecha_emision = $cuota->fecha_registro;
    $fecha_vencimiento = $cuota->fecha_vencimiento;

    $deudas = Deuda::with([
      'persona',
      'puesto',
    ])
    ->where('id_cuota', $id_cuota)
    ->get()
    ->map(function ($deuda) {

      $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
      $importe_pagado = $importeSuma ? $importeSuma : 0;

      return [
        'id_cuota' => $deuda->id_cuota,
        'nombre_completo' => $deuda->persona ? $deuda->persona->nombre_completo : '',
        'numero_puesto' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
        'area' => $deuda->puesto ? $deuda->puesto->area : '',
        'total' => $deuda->total_deuda,
        'importe_pagado' => $importe_pagado,
        'fecha_registro' => $deuda->fecha_registro,
      ];

    });

    $pdf = app(PDF::class)->loadView('exports.reporte_cuotas_metrado', [
      'fecha_emision' => $fecha_emision,
      'fecha_vencimiento' => $fecha_vencimiento,
      'deudas' => $deudas,
    ]);

    return $pdf->download('reporte_cuotas_metrado.pdf');

  }

}