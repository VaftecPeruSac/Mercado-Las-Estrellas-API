<?php

namespace App\Exports\PDF;

use App\Models\Socio;
use Barryvdh\DomPDF\PDF;

class SociosPDFExport {

  public function generatePDF() {

    $socios = Socio::with([
      'usuario.persona',
      'puesto.block',
      'puesto.gironegocio',
      'puesto.inquilino',
    ])->get()->map(function ($socio) {
      return [
        'nombre_usuario' => $socio->usuario->nombre_usuario ?? '------',
        'dni' => $socio->usuario->persona->dni ?? '------',
        'bloque' => $socio->puesto->block->nombre ?? '------',
        'puesto' => $socio->puesto->numero_puesto ?? '------',
        'giro' => $socio->puesto->gironegocio->nombre ?? '------',
        'telefono' => $socio->usuario->persona->telefono ?? '------',
        'correo' => $socio->usuario->persona->correo ?? '------',
        'inquilino' => $socio->puesto->inquilino->nombre_completo ?? '------',
        'fecha_registro' => $socio->fecha_registro ?? '------',
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.socios', ['socios' => $socios]);
    return $pdf->download('socios.pdf');

  }

}