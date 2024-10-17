<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReporteResumenPorPuestoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($detallePagos) {
                    
                    return [
                        'serie_numero' => $detallePagos->pago ? $detallePagos->pago->serie.'-'.$detallePagos->pago->numero_pago : '-',
                        'importe_ingreso' => $detallePagos->importe,
                        'importe_gastos_administrativo' => 0,
                        'importe_multas_inasistencia' => 0,
                        'importe_pagos_transferencia' => 0,
                        'importe_cuotas_extraordinarias' => 0,
                        'importe_total' => $detallePagos->importe,
                    ];
                }),
                'links' => [
                    'self' => url('/reportes/resumen-por-puestos'),
                ],
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
