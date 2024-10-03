<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;

class ReporteCuotaPorMetroCollection extends ResourceCollection
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
                // $importe_por_pagar = $deuda->total_deuda - $importeSuma;

                return [
                    'nombre_completo' => $deuda->persona ? $deuda->persona->nombre_completo : '',
                    'numero_puesto' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
                    'area' => $deuda->puesto ? $deuda->puesto->area : '',
                    'total' => $deuda->total_deuda,
                    'importe_pagado' => $importe_pagado,
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
