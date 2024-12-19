<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($servicio) {

                return [
                    'id_servicio' => $servicio->id_servicio,
                    'nombre' => $servicio->nombre,
                    'costo_unitario' => $servicio->costo_unitario,
                    'tipo_servicio' => $servicio->tipo_servicio,
                    'fecha_registro' => $servicio->fecha_registro,
                ];
            }),
            'links' => [
                'self' => url('/servicios'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
