<?php

namespace App\Exports\PDF;

use App\Models\Socio;
use Barryvdh\DomPDF\PDF;

class SociosPDFExport {

  public function generatePDF() {

    $socios = Socio::with(['usuarios'])->where('usuarios.estado', '0')->get()->map(function ($socio) {
      return [
        'nombre' => $socio->nombres.' '.$socio->apellido_paterno.' '.$socio->apellido_materno ?? '------', 
        'dni' => $socio->dni ?? '------', 
        'telefono' => $socio->telefono ?? '------', 
        'correo' => $socio->correo ?? '------', 
        'puestos' => $socio->puestos->map(function ($puesto) {
          return [
            'block' => $puesto->block->nombre,
            'giro' => $puesto->gironegocio->nombre,
            'numero' => $puesto->numero_puesto,
            'inquilino' => $puesto->inquilino->nombre_completo ?? '------',
          ];
        }),
        'inquilino' => $socio->puesto->inquilino->nombre_completo ?? '------', 
        'fecha_registro' => $socio->fecha_registro ?? '------',
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.socios', ['socios' => $socios]);
    return $pdf->download('socios.pdf');

  }

}