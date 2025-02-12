<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Puesto;
use App\Util\Util;
use Barryvdh\DomPDF\PDF;

class ReporteResumenPuestoPDFExport {

    public function generatePDF($id_puesto) {

        $puesto = Puesto::find($id_puesto);
        $nombre_socio = $puesto->socio->persona->nombre_completo;
        $nombre_bloque = $puesto->block ? $puesto->block->nombre : '-';
        $numero_puesto = $puesto->numero_puesto;
        $area = $puesto->area;
        $giro_negocio = $puesto->gironegocio ? $puesto->gironegocio->nombre : '-';

        $pagos = DetallePagos::with(['pago'])
            ->where('id_puesto', $id_puesto)
            ->get()
            ->map(function ($detallePagos) {
                return [
                    'numero_pago' => $detallePagos->pago ? $detallePagos->pago->serie.'-'.$detallePagos->pago->numero_pago : '-',
                    'importe_ingreso' => $detallePagos->importe,
                    'importe_gastos_administrativo' => 0,
                    'importe_multas_inasistencia' => 0,
                    'importe_pagos_transferencia' => 0,
                    'importe_cuotas_extraordinarias' => 0,
                    'importe_total' => $detallePagos->importe,
                ];
            });

        $pagosArray = json_decode(json_encode($pagos), true);
        $total_importe_ingreso = Util::sumaColArrayObjFormat($pagosArray, 'importe_ingreso');
        $total_importe_gastos_administrativo = Util::sumaColArrayObjFormat($pagosArray, 'importe_gastos_administrativo');
        $total_importe_multas_inasistencia = Util::sumaColArrayObjFormat($pagosArray, 'importe_multas_inasistencia');
        $total_importe_pagos_transferencia = Util::sumaColArrayObjFormat($pagosArray, 'importe_pagos_transferencia');
        $total_importe_cuotas_extraordinarias = Util::sumaColArrayObjFormat($pagosArray, 'importe_cuotas_extraordinarias');
        $total_importe_total = Util::sumaColArrayObjFormat($pagosArray, 'importe_total');

        $pdf = app(PDF::class)->loadView('exports.reporte_resumen_puesto', [
            'nombre_socio' => $nombre_socio,
            'nombre_bloque' => $nombre_bloque,
            'numero_puesto' => $numero_puesto,
            'area' => $area,
            'giro_negocio' => $giro_negocio,
            'pagos' => $pagos,
            'total_importe_ingreso' => $total_importe_ingreso,
            'total_importe_gastos_administrativo' => $total_importe_gastos_administrativo,
            'total_importe_multas_inasistencia' => $total_importe_multas_inasistencia,
            'total_importe_pagos_transferencia' => $total_importe_pagos_transferencia,
            'total_importe_cuotas_extraordinarias' => $total_importe_cuotas_extraordinarias,
            'total_importe_total' => $total_importe_total,
        ]);

        return $pdf->download('reporte_resumen_puesto.pdf');

    }

}