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
                    'id_socio' => $socio->id_socio,
                    'id_usuario' => $socio->id_usuario,
                    'tipo_persona' => $socio->tipo_persona,
                    'saldo' => $socio->saldo,
                    'fecha_registro' => $socio->fecha_registro,
                    // 'id_persona' => $socio->id_persona,
                    // 'persona' => new PersonaResource($socio->persona), // Si estás incluyendo sociosc
                    // 'gironegocio' => new GiroNegocioResource($socio->gironegocio), //
                    // 'puestos' => new PuestoResource($socio->puestos), // Si estás incluyendo socios
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
