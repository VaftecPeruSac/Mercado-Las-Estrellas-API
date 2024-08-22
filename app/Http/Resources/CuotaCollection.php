<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CuotaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($cuota) {
                    
                    return [
                        'importe' => $cuota->importe,
                        'fecha_vencimiento' => $cuota->fecha_vencimiento,
                        'fecha_registro' => $cuota->fecha_registro,
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
