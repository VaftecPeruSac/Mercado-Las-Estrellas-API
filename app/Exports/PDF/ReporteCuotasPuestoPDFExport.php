<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\Puesto;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class ReporteCuotasPuestoPDFExport {

  public function generatePDF($id_puesto) {

    $puesto = Puesto::find($id_puesto);
    $nombre_socio = $puesto->socio->usuario->nombre_usuario;
    $nombre_bloque = $puesto->block->nombre;
    $numero_puesto = $puesto->numero_puesto;
    $area = $puesto->area;
    $giro_negocio = $puesto->gironegocio->nombre;

    $deudas = Deuda::with(['servicio'])
    ->where('id_puesto', $id_puesto)
    ->get()
    ->map(function ($deuda) {

      $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
      $importe_pagado = $importeSuma ? $importeSuma : 0;
      $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

      return [
        'id_cuota' => $deuda->id_cuota,
        'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
        'servicio_descripcion' => $deuda->servicio ? $deuda->servicio->descripcion : '',
        'total_deuda' => $deuda->total_deuda,
        'importe_pagado' => $importe_pagado,
        'importe_por_pagar' => $importe_por_pagar,
        'fecha_registro' => $deuda->fecha_registro,
      ];

    });

    $pdf = app(PDF::class)->loadView('exports.reporte_cuotas_puesto', [
      'nombre_socio' => $nombre_socio,
      'nombre_bloque' => $nombre_bloque,
      'numero_puesto' => $numero_puesto,
      'area' => $area,
      'giro_negocio' => $giro_negocio,
      'deudas' => $deudas,
    ]);

    return $pdf->download('reporte_cuotas_puesto.pdf');

  }

}