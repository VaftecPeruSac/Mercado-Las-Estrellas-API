<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Puesto;
use Barryvdh\DomPDF\PDF;

class ReporteResumenPuestoPDFExport {

  public function generatePDF($id_puesto) {

    $puesto = Puesto::find($id_puesto);
    $nombre_socio = $puesto->socio->usuario->nombre_usuario;
    $nombre_bloque = $puesto->block->nombre;
    $numero_puesto = $puesto->numero_puesto;
    $area = $puesto->area;
    $giro_negocio = $puesto->gironegocio->nombre;

    $pagos = DetallePagos::with(['pago'])
    ->where('id_puesto', $id_puesto)
    ->get()
    ->map(function ($detallePagos) {
      return [
        'numero_pago' => $detallePagos->pago ? $detallePagos->pago->serie.'-'.$detallePagos->pago->numero_pago : '-',
        'importe_ingreso' => $detallePagos->importe,
        'importe_gastos_administrativo' => 0,
        'importe_multas_inasistencia' => 0,
        'importe_pagos_transferencia' => 0,
        'importe_cuotas_extraordinarias' => 0,
        'importe_total' => $detallePagos->importe,
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.reporte_resumen_puesto', [
      'nombre_socio' => $nombre_socio,
      'nombre_bloque' => $nombre_bloque,
      'numero_puesto' => $numero_puesto,
      'area' => $area,
      'giro_negocio' => $giro_negocio,
      'pagos' => $pagos,
    ]);

    return $pdf->download('reporte_resumen_puesto.pdf');

  }

}