<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;
use Carbon\Carbon;

class ReporteCuotaPorPuestoCollection extends ResourceCollection
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

                $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                $importe_pagado = $importeSuma ? $importeSuma : 0;
                $importe_por_pagar = $deuda->total_deuda - $importe_pagado;

                return [
                    'id_cuota' => $deuda->id_cuota,
                    'anio' => (new Carbon( $deuda->fecha_registro ))->format('Y'),
                    'servicio_descripcion' => $deuda->servicio ? $deuda->servicio->descripcion : '',
                    'aprobado' => $deuda->total_deuda,
                    'pagado' => $importe_pagado,
                    'por_pagar' => $importe_por_pagar,
                    'fecha' => $deuda->fecha_registro,
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
