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
            //Se listan por Puestos porque  un socio tiene muchos puestos y se quiere traer todos los registros
            'data' => $this->collection->transform(function ($puesto) {
                return [
                    // 'id_puesto' => $puesto->id_puesto,
                    // 'id_socio' => $puesto->id_socio,
                    // 'id_gironegocio' => $puesto->id_gironegocio,
                    // 'id_block' => $puesto->id_block,
                    'numero_puesto' => $puesto->numero_puesto,
                    // 'area' => $puesto->area,
                    'id_inquilino' => $puesto->id_inquilino,
                    'estado' => $puesto->estado,
                    'fecha_registro' => $puesto->socio && $puesto->socio->usuario ? $puesto->socio->usuario->fecha_registro : 'no',
                    'socio' => $puesto->socio && $puesto->socio->usuario && $puesto->socio->usuario->persona ? $puesto->socio->usuario->persona->nombre : 'no',
                    'dni' => $puesto->socio && $puesto->socio->usuario && $puesto->socio->usuario->persona ? $puesto->socio->usuario->persona->dni : 'no',
                    'telefono' => $puesto->socio && $puesto->socio->usuario && $puesto->socio->usuario->persona ? $puesto->socio->usuario->persona->telefono : 'no',
                    'correo' => $puesto->socio && $puesto->socio->usuario && $puesto->socio->usuario->persona ? $puesto->socio->usuario->persona->correo : 'no',
                    'gironegocio_nombre' => $puesto->gironegocio ? $puesto->gironegocio->nombre : 'no',
                    'block_nombre' => $puesto->block ? $puesto->block->nombre : 'no',
                    'inquilino' => $puesto->inquilino ? $puesto->inquilino->nombre_completo : 'no',
                    'deuda' => $puesto->deuda ? $puesto->deuda->total_deuda : 'no',
                    // 'socio' => new SocioResource($puesto->socio),
                    // 'gironegocio' => new GiroNegocioResource($puesto->gironegocio), // Si estás incluyendo sociosc
                    // 'block' => new BlockResource($puesto->block),
                    // 'inquilino' => new InquilinoResource($puesto->inquilino),
                    // 'deuda' => new DeudaResource($puesto->deuda),
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
