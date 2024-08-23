<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DeudaCollection extends ResourceCollection
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
                    'id_socio' => $deuda->id_socio,
                    'total_deuda' => $deuda->total_deuda,
                    'fecha_registro' => $deuda->fecha_registro,
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
