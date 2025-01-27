<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;
use Illuminate\Support\Facades\DB;

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
                        'id_pago' => $pago->id_pago,
                        'numero' => $pago->numero_pago,
                        'serie' => $pago->serie,
                        'serie_numero' => $pago->serie.'-'.$pago->numero_pago,
                        'aporte' => $pago->total_pago,
                        'total' => $pago->total_pago,
                        'fecha' => $pago->fecha_registro,
                        'detalle_pagos' => DetallePagos::join('servicios','detalle_pagos.id_servicio','servicios.id_servicio')
                            ->select('detalle_pagos.importe',DB::raw("servicios.nombre as descripcion"))
                            ->where('detalle_pagos.id_pago',$pago->id_pago)->get(),
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
