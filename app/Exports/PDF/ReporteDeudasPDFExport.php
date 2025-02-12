<?php

namespace App\Exports\PDF;

use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\Puesto;
use App\Models\SetupMes;
use App\Models\DeudaCuota;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class ReporteDeudasPDFExport {

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
                $anio = (new Carbon($deuda->fecha_registro))->format('Y');

                $mes = '';
                $mesCarbon = (new Carbon( $deuda->fecha_registro ))->format('m');
                $mesCarbon = (int)$mesCarbon;
                $mes = (SetupMes::find($mesCarbon))->nombre;

                $deudaCuotas = DeudaCuota::select('c.nombre')
                    ->join('cuota_servicios as b','deuda_cuotas.id_cuota_servicio','b.id_cuota_servicio')
                    ->join('servicios as c','b.id_servicio','c.id_servicio')
                    ->where('deuda_cuotas.id_deuda',$deuda->id_deuda)
                    ->groupBy('c.nombre')->get();
                $servicio_nombres = implode(', ', $deudaCuotas->pluck('nombre')->toArray());

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ?? 0;
                $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

                return [
                    'anio' => $anio,
                    'mes' => $mes,
                    'servicio_descripcion' => $servicio_nombres,
                    'total' => number_format($deuda->total_deuda, 2, '.', ''),
                    'importe_pagado' => number_format($importe_pagado, 2, '.', ''),
                    'importe_por_pagar' => number_format($importe_por_pagar, 2, '.', ''),
                ];

            });

        $deudasArray = json_decode(json_encode($deudas), true);
        $total = array_sum(array_column($deudasArray, 'total'));
        $total = number_format($total, 2, '.', '');
        $importe_pagado = array_sum(array_column($deudasArray, 'importe_pagado'));
        $total_importe_pagado = number_format($importe_pagado, 2, '.', '');
        $importe_por_pagar = array_sum(array_column($deudasArray, 'importe_por_pagar'));
        $total_importe_por_pagar = number_format($importe_por_pagar, 2, '.', '');

        $pdf = app(PDF::class)->loadView('exports.reporte_deudas', [
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

        return $pdf->download('reporte_deudas.pdf');

    }

}