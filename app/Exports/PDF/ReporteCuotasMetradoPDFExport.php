<?php

namespace App\Exports\PDF;

use App\Models\Cuota;
use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Util\Util;
use Barryvdh\DomPDF\PDF;

class ReporteCuotasMetradoPDFExport {

    public function generatePDF($id_cuota) {

        $cuota = Cuota::find($id_cuota);
        $fecha_emision = $cuota->fecha_registro;
        $fecha_vencimiento = $cuota->fecha_vencimiento;

        $deudas = Deuda::whereExists(function ($query) use ($id_cuota) {
                $query->select("deuda_cuotas.id_deuda")
                    ->from('deuda_cuotas')
                    ->join('cuota_servicios','deuda_cuotas.id_cuota_servicio','cuota_servicios.id_cuota_servicio')
                    ->whereRaw('deudas.id_deuda = deuda_cuotas.id_deuda')
                    ->where('cuota_servicios.id_cuota', $id_cuota);
            })
            ->get()
            ->map(function ($deuda) use ($id_cuota) {

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ? $importeSuma : 0;

                return [
                    'id_cuota' => $id_cuota,
                    'nombre_completo' => $deuda->socio && $deuda->socio->persona ? $deuda->socio->persona->nombre_completo : '',
                    'numero_puesto' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
                    'area' => $deuda->puesto ? $deuda->puesto->area : '',
                    'total' => $deuda->total_deuda,
                    'importe_pagado' => $importe_pagado,
                    'fecha_registro' => $deuda->fecha_registro,
                ];

            });

        $deudasArray = json_decode(json_encode($deudas), true);
        $total = Util::sumaColArrayObjFormat($deudasArray, 'total');
        $total_importe_pagado = Util::sumaColArrayObjFormat($deudasArray, 'importe_pagado');

        $pdf = app(PDF::class)->loadView('exports.reporte_cuotas_metrado', [
            'fecha_emision' => $fecha_emision,
            'fecha_vencimiento' => $fecha_vencimiento,
            'deudas' => $deudas,
            'total' => $total,
            'total_importe_pagado' => $total_importe_pagado,
        ]);

        return $pdf->download('reporte_cuotas_metrado.pdf');

    }

}