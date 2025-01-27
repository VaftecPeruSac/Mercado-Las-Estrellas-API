<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;
use App\Models\Deuda;

class PagoCollection extends ResourceCollection
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
                $puestos = DetallePagos::select('b.id_puesto','b.numero_puesto')
                    ->join('puestos as b','detalle_pagos.id_puesto','b.id_puesto')
                    ->where('id_pago',$pago->id_pago)
                    ->groupBy('b.id_puesto','b.numero_puesto')->get();
                $idsPuesto = $puestos->pluck('id_puesto')->toArray();
                $puesto = implode(', ', $puestos->pluck('numero_puesto')->toArray());

                $importePago = DetallePagos::select('importe')
                    ->whereIn('id_puesto',$idsPuesto)
                    ->sum('importe');
                $importeDeuda = Deuda::select('total_deuda')
                    ->whereIn('id_puesto',$idsPuesto)
                    ->sum('total_deuda');
                $total_deuda = ($importeDeuda ?? 0) - ($importePago ?? 0);

                return [
                    'id_pago' => $pago->id_pago, 
                    'puesto' => $puesto,
                    'socio' => $pago->socio && $pago->socio->persona ? $pago->socio->persona->nombre_completo : 'no',
                    'dni' => $pago->socio && $pago->socio->persona ? $pago->socio->persona->dni : 'no',
                    'telefono' => $pago->socio && $pago->socio->persona ? $pago->socio->persona->telefono : 'no',
                    'correo' => $pago->socio && $pago->socio->persona ? $pago->socio->persona->correo : 'no',
                    'numero' => $pago->numero_pago,
                    'serie' => $pago->serie,
                    'serie_numero' => $pago->serie.'-'.$pago->numero_pago,
                    'total_pago' => $pago->total_pago,
                    'total_deuda' => number_format($total_deuda, 2, '.', ','),
                    'fecha_registro' => $pago->fecha_registro,
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
