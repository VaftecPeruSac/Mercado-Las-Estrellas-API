<?php

namespace App\Exports\PDF;

use App\Models\Servicio;
use Barryvdh\DomPDF\PDF;

class ServicioPDFExport {

  public function generatePDF() {

    $servicios = Servicio::with([
    ])->get()->map(function($servicio) {
      return [
        'id' => $servicio->id_servicio ?? '------', 
        'descripcion' => $servicio->descripcion?? '------', 
        'costo_unitario' => $servicio->costo_unitario ?? '------', 
        'tipo_servicio' => $servicio->tipo_servicio === 3 ? 'Servicio por metros cuadrados' : ($servicio->tipo_servicio === 2 ? 'Extraordinario' : 'Ordinario'),
        'fecha_registro' => $servicio->fecha_registro ?? '------', 
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.servicios', ['servicios' => $servicios]);
    return $pdf->download('servicios.pdf');

  }

}