<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;

class ReportePagoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($pago) {
                    
                    return [
                        'numero' => $pago->numero_pago,
                        'serie' => $pago->serie,
                        'aporte' => $pago->total_pago,
                        'total' => $pago->total_pago,
                        'fecha' => $pago->fecha_registro,
                        'detalle_pagos' => DetallePagos::join('deudas','detalle_pagos.id_deuda','deudas.id_deuda')
                            ->join('servicios','deudas.id_servicio','servicios.id_servicio')
                            ->select('servicios.descripcion','detalle_pagos.importe')->where('detalle_pagos.id_pago',$pago->id_pago)->get(),
                    ];
                }),
                'links' => [
                    'self' => url('/reportes/pagos'),
                ],
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
