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
            'data' => $this->collection->transform(function ($socio) {
                return [
                    // 'id_puesto' => $puesto->id_puesto,
                    'id_socio' => $socio->id_socio,
                    // 'id_gironegocio' => $puesto->id_gironegocio,
                    // 'id_block' => $puesto->id_block,
                    'numero_puesto' => $socio->puesto ? $socio->puesto->numero_puesto:'no',
                    // 'area' => $puesto->area,
                    'id_inquilino' =>  $socio->puesto ? $socio->puesto->id_inquilino:'no',
                    'estado' =>  $socio->puesto ? $socio->puesto->estado:'no',
                    'fecha_registro' =>$socio->usuario ? $socio->usuario->fecha_registro : 'no',
                    'socio' =>$socio->usuario ? $socio->usuario->nombre_usuario : 'no',
                    'dni' =>$socio->usuario->persona->dni,
                    'telefono' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->telefono : 'no',
                    'correo' =>$socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->correo : 'no',
                    'gironegocio_nombre' => $socio->puesto && $socio->puesto->gironegocio ? $socio->puesto->gironegocio->nombre : 'no',
                    'block_nombre' =>$socio->puesto && $socio->puesto->block ? $socio->puesto->block->nombre : 'no',
                    'inquilino' =>$socio->puesto && $socio->puesto->inquilino ? $socio->puesto->inquilino->nombre_completo : 'no',
                    'deuda' =>$socio->puesto && $socio->puesto->deuda ? $socio->puesto->deuda->total_deuda : 'no',
                    // 'socio' => new SocioResource($puesto->socio),
                    // 'gironegocio' => new GiroNegocioResource($puesto->gironegocio), // Si estás incluyendo sociosc
                    // 'block' => new BlockResource($puesto->block),
                    // 'inquilino' => new InquilinoResource($puesto->inquilino),
                    // 'deuda' => new DeudaResource($puesto->deuda),
                    'nombre' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->nombre : '',
                    'apellido_paterno' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->apellido_paterno : '',
                    'apellido_materno' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->apellido_materno : '',
                    'direccion' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->direccion : '',
                    'sexo' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->sexo : '',
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
