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
                    'id_persona' => $persona->id_persona,
                    'nombre' => $persona->nombre,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno,
                    'dni' => $persona->dni,
                    'correo' => $persona->correo,
                    'telefono' => $persona->telefono,
                    'direccion' => $persona->direccion,
                    'sexo' => $persona->sexo,
                    'estado' => $persona->estado,
                    'fecha_registro' => $persona->fecha_registro,
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
