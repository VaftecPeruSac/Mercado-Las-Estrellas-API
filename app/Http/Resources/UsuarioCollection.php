<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsuarioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($usuario) {
                
                return [
                    'id_usuario' => $usuario->id_usuario,
                    'id_persona' => $usuario->id_persona,
                    'nombre_usuario' => $usuario->nombre_usuario,
                    'contrasenia' => $usuario->contrasenia,
                    'rol' => $usuario->rol,
                    'estado' => $usuario->estado,
                    'fecha_registro' => $usuario->fecha_registro,
                    'persona' => new PersonaResource($usuario->persona),
                    
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
