<?php

namespace App\Exports\PDF;

use App\Models\Pago;
use Barryvdh\DomPDF\PDF;

class PagosPDFExport {

  public function generatePDF() {

    $pagos = Pago::with([
      'socio.puesto',
      'socio.usuario.persona',
      'socio.deuda.Cuota', // Relación correcta en plural
      'socio.puesto',
    ])->get()->map(function($pago) {
      $a_cuenta = '------';

      // Verificar si hay deudas y cuotas relacionadas
      if ($pago->socio->deuda && $pago->socio->deuda->Cuota) {
          $cuota = $pago->socio->deuda->Cuota;
          if (isset($cuota->pivot)) {
            $a_cuenta = $cuota->pivot->a_cuenta ?? '------'; 
          }
      }

      return [
          'id' => $pago->id_pago?? '------', 
          'numero_puesto' => $pago->socio->puesto->numero_puesto ?? '------', 
          'socio' => $pago->socio->usuario->nombre_usuario ?? '------', 
          'dni' => $pago->socio->usuario->persona->dni ?? '------', 
          'fecha_registro' => $pago->fecha_registro ?? '------', 
          'telefono' => $pago->socio->usuario->persona->telefono ?? '------', 
          'correo' => $pago->socio->usuario->persona->correo ?? '------', 
          'a_cuenta' => $a_cuenta,
          'monto_total' => $pago->total_pago ?? '------', 
      ];
    });

    $pdf = app(PDF::class)->loadView('exports.pagos', ['pagos' => $pagos]);
    return $pdf->download('pagos.pdf');

  }

}