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
    ])->where('activo', true)->get()->map(function($puesto) {
      return [
        'bloque' => $puesto->block->nombre ?? '------', 
        'puesto' => $puesto->numero_puesto ?? '------', 
        'area' => $puesto->area ?? '------', 
        'giro' => $puesto->gironegocio->nombre ?? '------', 
        'socio' => $puesto->socio->nombres.' '.$puesto->socio->apellido_paterno.' '.$puesto->socio->apellido_materno ?? '------', 
        'inquilino' => $puesto->inquilino->nombre.' '.$puesto->inquilino->apellido_paterno.' '.$puesto->inquilino->apellido_maaterno ?? '------', 
        'estado' => $puesto->estado === '1' ?  'Libre' : 'Ocupado',
        'fecha_registro' => $fecha_registro ?? '------', 
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.puestos', ['puestos' => $puestos]);
    return $pdf->download('puestos.pdf');

  }

}