<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DeudaAndCuotaCollection extends ResourceCollection
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
                return [
                    'id_deuda' => $deuda->id_deuda,
                    'id_cuota' => $deuda->id_cuota,
                    'id_socio' => $deuda->id_socio,
                    'importe' => $deuda->total_deuda,
                    'fecha_registro' => $deuda->fecha_registro,
                    'fecha_vencimiento' => $deuda->cuota ? $deuda->cuota->fecha_vencimiento : null,
                    'socio_nombre' => $deuda->persona ? $deuda->persona->nombre_completo : '',
                    'puesto_descripcion' => $deuda->puesto ? $deuda->puesto->numero_puesto : '',
                    'servicio_descripcion' => $deuda->servicio ? $deuda->servicio->descripcion : '',
                ];
            }),
            'links' => [
                'self' => url('/cuotas'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
