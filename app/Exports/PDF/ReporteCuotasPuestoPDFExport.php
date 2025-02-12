<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\Puesto;
use App\Models\DeudaCuota;
use App\Util\Util;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class ReporteCuotasPuestoPDFExport {

    public function generatePDF($id_puesto) {

        $puesto = Puesto::find($id_puesto);
        $nombre_socio = $puesto->socio->persona->nombre_completo;
        $nombre_bloque = $puesto->block ? $puesto->block->nombre : '-';
        $numero_puesto = $puesto->numero_puesto;
        $area = $puesto->area;
        $giro_negocio = $puesto->gironegocio ? $puesto->gironegocio->nombre : '-';

        $deudas = Deuda::where('id_puesto', $id_puesto)
            ->get()
            ->map(function ($deuda) {
                $deudaCuotas = DeudaCuota::select('c.nombre')
                    ->join('cuota_servicios as b','deuda_cuotas.id_cuota_servicio','b.id_cuota_servicio')
                    ->join('servicios as c','b.id_servicio','c.id_servicio')
                    ->where('deuda_cuotas.id_deuda',$deuda->id_deuda)
                    ->groupBy('c.nombre')->get();
                $servicio_nombres = implode(', ', $deudaCuotas->pluck('nombre')->toArray());

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ? $importeSuma : 0;
                $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

                return [
                    'id_cuota' => $deuda->id_cuota,
                    'anio' => (new Carbon($deuda->fecha_registro))->format('Y'),
                    'servicio_descripcion' => $servicio_nombres,
                    'total_deuda' => $deuda->total_deuda,
                    'importe_pagado' => $importe_pagado,
                    'importe_por_pagar' => $importe_por_pagar,
                    'fecha_registro' => $deuda->fecha_registro,
                ];

            });

        $deudasArray = json_decode(json_encode($deudas), true);
        $total = Util::sumaColArrayObjFormat($deudasArray, 'total_deuda');
        $total_importe_pagado = Util::sumaColArrayObjFormat($deudasArray, 'importe_pagado');
        $total_importe_por_pagar = Util::sumaColArrayObjFormat($deudasArray, 'importe_por_pagar');

        $pdf = app(PDF::class)->loadView('exports.reporte_cuotas_puesto', [
            'nombre_socio' => $nombre_socio,
            'nombre_bloque' => $nombre_bloque,
            'numero_puesto' => $numero_puesto,
            'area' => $area,
            'giro_negocio' => $giro_negocio,
            'deudas' => $deudas,
            'total' => $total,
            'total_importe_pagado' => $total_importe_pagado,
            'total_importe_por_pagar' => $total_importe_por_pagar,
        ]);

        return $pdf->download('reporte_cuotas_puesto.pdf');

    }

}