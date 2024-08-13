<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($socio) {

                return [
                    'id' => $socio->id,
                    'estado' => $socio->estado,
                    'telefono' => $socio->telefono, 
                    'correo' => $socio->correo,
                    'id_gironegocio' => $socio->id_gironegocio,
                    // 'id_persona' => $socio->id_persona,
                    'persona' => new PersonaResource($socio->persona), // Si estás incluyendo socios
                    'puestos' => new PuestoResource($socio->puestos), // Si estás incluyendo socios
                ];
            }),
            'links' => [
                'self' => url('/socios'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }

}
