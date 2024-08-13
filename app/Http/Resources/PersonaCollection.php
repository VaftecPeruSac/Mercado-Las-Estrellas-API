<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
   public function toArray($request)
{
        return [
            'data' => $this->collection->transform(function ($persona) {
                
                return [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'apellidoP' => $persona->apellidoP,
                    'apellidoM' => $persona->apellidoM,
                    'dni' => $persona->dni,
                    'estado' => $persona->estado,
                    // 'socio' => new SocioResource($persona->socio), // Si estás incluyendo socios
                ];
            }),
            'links' => [
                'self' => url('/personas'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
