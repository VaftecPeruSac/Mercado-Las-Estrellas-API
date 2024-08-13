<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PuestoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($puesto) {

                return [
                    'id' => $puesto->id,
                    'nombre' => $puesto->nombre,
                    'id_socio' => $puesto->id_socio,
                    'id_block' => $puesto->id_block,
                    'area' => $puesto->area,
                    'estado' => $puesto->estado,
                    'socio' => new SocioResource($puesto->socio), // Si estás incluyendo socios
                    'persona' => new PersonaResource($puesto->socio->persona),
                ];
            }),
            'links' => [
                'self' => url('/puestos'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
