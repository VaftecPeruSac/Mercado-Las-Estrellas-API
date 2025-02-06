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
                    'numero_puesto' => $puesto->numero_puesto,
                    'area' => $puesto->area,
                    'estado' => $puesto->estado,
                    'fecha_registro' => $puesto->fecha_registro,
                    'socio' => $puesto->socio && $puesto->socio->persona ? $puesto->socio->persona->nombre_completo : 'No asignado',
                    'giro_negocio' => $puesto->gironegocio ? $puesto->gironegocio : 'no',
                    'block' => $puesto->block ? $puesto->block : 'no',
                    'inquilino' => $puesto->inquilino ? $puesto->inquilino : 'No asignado',
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
