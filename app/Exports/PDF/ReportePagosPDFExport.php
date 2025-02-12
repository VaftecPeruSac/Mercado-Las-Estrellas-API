<?php

namespace App\Exports\PDF;

use App\Models\Pago;
use App\Models\Socio;
use Barryvdh\DomPDF\PDF;

class ReportePagosPDFExport {

    public function generatePDF($id_socio) {

        $socio = Socio::find($id_socio);
        $nombre_socio = $socio->usuario->nombre_usuario;

        $pagos = Pago::where('id_socio', $id_socio)
            ->get()
            ->map(function ($pago) {
                return [
                    'numero' => $pago->numero_pago,
                    'serie_numero' => $pago->serie.'-'.$pago->numero_pago,
                    'fecha' => $pago->fecha_registro,
                    'aporte' => $pago->total_pago,
                    'total' => $pago->total_pago,
                    'detalle_pagos' => $pago->detallePagos->map(function ($detalle) {
                            return $detalle->servicio->nombre . ': ' . $detalle->importe;
                        })->join('\n'), // Une los detalles en una sola cadena
                    'detalles' => $pago->detallePagos->map(function ($detalle) {
                            return [
                                'servicio_nombre' => $detalle->servicio->nombre,
                                'importe' => $detalle->importe,
                            ];
                        }),
                ];
            });

        $total = Pago::where('id_socio', $id_socio)->sum('total_pago');

        $pdf = app(PDF::class)->loadView('exports.reporte_pagos', [
            'nombre_socio' => $nombre_socio,
            'pagos' => $pagos,
            'total' => $total,
        ]);

        return $pdf->download('reporte_pagos.pdf');

    }

}