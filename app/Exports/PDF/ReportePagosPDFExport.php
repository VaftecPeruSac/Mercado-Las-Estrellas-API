<?php

namespace App\Exports\PDF;

use App\Models\Pago;
use App\Models\Socio;
use Barryvdh\DomPDF\PDF;

class ReportePagosPDFExport {

  public function generatePDF($id_socio) {

    $socio = Socio::find($id_socio);
    $nombre_socio = $socio->usuario->nombre_usuario;

    $pagos = Pago::with([
      'detallePagos.deuda.servicio',
    ])
    ->where('id_socio', $id_socio)
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

    $pdf = app(PDF::class)->loadView('exports.reporte_pagos', [
      'nombre_socio' => $nombre_socio,
      'pagos' => $pagos, 
    ]);

    return $pdf->download('reporte_pagos.pdf');

  }

}