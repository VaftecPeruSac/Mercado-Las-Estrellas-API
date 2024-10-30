<?php

namespace App\Exports\PDF;

use App\Models\Puesto;
use Barryvdh\DomPDF\PDF;

class PuestosPDFExport {

  public function generatePDF() {

    $puestos = Puesto::with([
      'socio.usuario',
      'block',
      'gironegocio',
      'inquilino' 
    ])->get()->map(function($puesto) {
      return [
        'bloque' => $puesto->block->nombre ?? '------', 
        'puesto' => $puesto->numero_puesto ?? '------', 
        'area' => $puesto->area ?? '------', 
        'giro' => $puesto->gironegocio->nombre ?? '------', 
        'socio' => $puesto->socio->usuario->nombre_usuario ?? '------', 
        'inquilino' => $puesto->inquilino->nombre_completo ?? '------', 
        'estado' => $puesto->estado ?? '------', 
        'fecha_registro' => $fecha_registro ?? '------', 
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.puestos', ['puestos' => $puestos]);
    return $pdf->download('puestos.pdf');

  }

}