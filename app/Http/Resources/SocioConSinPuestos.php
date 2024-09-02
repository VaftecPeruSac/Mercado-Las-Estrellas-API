<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocioConSinPuestos extends ResourceCollection
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
                    'id_puesto' => $socio->puesto ? $socio->puesto->id_puesto : '',
                    'numero_puesto' => $socio->puesto ? $socio->puesto->numero_puesto : '',
                    'id_inquilino' =>  $socio->puesto ? $socio->puesto->id_inquilino:'',
                    'area' => $socio->puesto ? $socio->puesto->area : '',
                    'estado' => $socio->puesto ? $socio->puesto->estado : '',
                    'fecha_registro' => $socio->puesto && $socio->puesto->fecha_registro ? $socio->puesto->fecha_registro : null,
                    'socio' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->nombre_completo : 'no',
                    'dni' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->dni : 'no',
                    'gironegocio_nombre' => $socio->puesto && $socio->puesto->gironegocio ? $socio->puesto->gironegocio->nombre : 'no',
                    'block_nombre' => $socio->puesto && $socio->puesto->block ? $socio->puesto->block->nombre : 'no',
                    'inquilino' => $socio->puesto && $socio->puesto->inquilino ? $socio->puesto->inquilino->nombre_completo : 'no',
                    'telefono' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->telefono : 'no',
                    'correo' =>$socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->correo : 'no',
                    // 'inquilino' =>$socio->puesto && $socio->puesto->inquilino ? $socio->puesto->inquilino->nombre_completo : 'no',
                    'deuda' =>$socio->puesto && $socio->puesto->deuda ? $socio->puesto->deuda->total_deuda : 'no',
                ];
            }),
            'links' => [
                'self' => url('/socios/consin-puestos'),
            ],
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
