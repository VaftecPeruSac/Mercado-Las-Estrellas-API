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
                    'id_puesto' => $puesto->id_puesto,
                    'id_socio' => $puesto->id_socio,
                    'id_gironegocio' => $puesto->id_gironegocio,
                    'id_block' => $puesto->id_block,
                    'numero_puesto' => $puesto->numero_puesto,
                    'area' => $puesto->area,
                    'id_inquilino' => $puesto->id_inquilino,
                    'estado' => $puesto->estado,
                    'fecha_registro' => $puesto->fecha_registro,
                    'socio' => new SocioResource($puesto->socio),
                    'gironegocio' => new GiroNegocioResource($puesto->gironegocio), // Si estás incluyendo sociosc
                    'block' => new BlockResource($puesto->block),
                    'inquilino' => new InquilinoResource($puesto->inquilino),
                    // 'deuda' => new DeudaResource($puesto->deuda),
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
