<?php

namespace App\Exports\PDF;

use App\Models\Cuota;
use Barryvdh\DomPDF\PDF;

class CuotaPDFExport {

  public function generatePDF() {
  
    $cuotas = Cuota::with([
    ])->get()->map(function($cuota) {
      return [
        'id' => $cuota->id_cuota ?? '------', 
        'fecha_registro' => $cuota->fecha_registro ?? '------', 
        'fecha_vencimiento' => $cuota->fecha_vencimiento ?? '------', 
        'importe' => $cuota->importe ?? '------', 
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.cuotas', ['cuotas' => $cuotas]);
    return $pdf->download('cuotas.pdf');

  }

}