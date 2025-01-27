<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DeudaCuota;
use App\Models\DetallePagos;
use App\Models\Servicio;
use App\Models\SetupMes;
use Carbon\Carbon;

class ReporteDeudaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($deuda) {
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
                    $importe_pagado = $importeSuma ? $importeSuma : 0;
                    $importe_por_pagar = $deuda->total_deuda - $importeSuma;

                    return [
                        'id_cuota' => $deuda->id_cuota,
                        'anio' => (new Carbon( $deuda->fecha_registro ))->format('Y'),
                        'mes' => $mes,
                        'servicio_descripcion' => $servicio_nombres,
                        'total' => $deuda->total_deuda,
                        'importe_pagado' => $importe_pagado,
                        'importe_por_pagar' => $importe_por_pagar,
                    ];
                }),
                'links' => [
                    'self' => url('/pagos'),
                ],
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
