<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\Puesto;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class ReporteDeudasPDFExport {

  public function generatePDF($id_puesto) {

    $puesto = Puesto::find($id_puesto);
    $nombre_socio = $puesto->socio->usuario->nombre_usuario;
    $nombre_bloque = $puesto->block->nombre;
    $numero_puesto = $puesto->numero_puesto;
    $area = $puesto->area;
    $giro_negocio = $puesto->gironegocio->nombre;

    $deudas = Deuda::with(['servicio',])
    ->where('id_puesto', $id_puesto)
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
        'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
        'mes' => $mes,
        'servicio_descripcion' => $deuda->servicio->descripcion ?? '-----',
        'total' => $deuda->total_deuda ?? 0,
        'importe_pagado' => $importe_pagado ?? 0,
        'importe_por_pagar' => $importe_por_pagar ?? 0,
      ];

    });

    $pdf = app(PDF::class)->loadView('exports.reporte_deudas', [
      'nombre_socio' => $nombre_socio,
      'nombre_bloque' => $nombre_bloque,
      'numero_puesto' => $numero_puesto,
      'area' => $area,
      'giro_negocio' => $giro_negocio,
      'deudas' => $deudas,
    ]);

    return $pdf->download('reporte_deudas.pdf');

  }

}