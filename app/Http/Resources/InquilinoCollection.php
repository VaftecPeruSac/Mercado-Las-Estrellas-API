<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InquilinoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($inquilino) {
                return [
                    'id_inquilino' => $inquilino->id_inquilino,
                    'nombre_completo' => $inquilino->nombre_completo,
                    'apellido_paterno' => $inquilino->apellido_paterno,
                    'apellido_materno' => $inquilino->apellido_materno,
                    'dni' => $inquilino->dni,
                    'telefono' => $inquilino->telefono,
                ];
            }),
            'links' => [
                'self' => url('/inquilinos'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
