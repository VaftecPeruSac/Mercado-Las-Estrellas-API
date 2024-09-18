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
                    'id_socio' => $socio->id_socio,
                    'nombre_completo' => $socio->usuario->nombre_usuario,
                    'datos_socio' => $socio->usuario->persona ? [
                        'nombre' => $socio->usuario->persona->nombre,
                        'apellido_paterno' => $socio->usuario->persona->apellido_paterno,
                        'apellido_materno' => $socio->usuario->persona->apellido_materno,
                        'dni' => $socio->usuario->persona->dni ? $socio->usuario->persona->dni : 'No',
                        'sexo' => $socio->usuario->persona->sexo,
                        'direccion' => $socio->usuario->persona->direccion ? $socio->usuario->persona->direccion : 'No',
                        'telefono' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->telefono : 'No',
                        'correo' => $socio->usuario && $socio->usuario->persona ? $socio->usuario->persona->correo : 'No',
                    ] : 'No',
                    'puesto' => $socio->puesto ? [
                        'id_puesto' => $socio->puesto->id_puesto,
                        'bloque' => $socio->puesto->block,
                        'gironegocio_nombre' => $socio->puesto && $socio->puesto->gironegocio ? $socio->puesto->gironegocio->nombre : 'No',
                        'inquilino' =>  $socio->puesto->inquilino ? [
                            'id_inquilino' => $socio->puesto->id_inquilino,
                            'nombre_completo' =>$socio->puesto && $socio->puesto->inquilino ? $socio->puesto->inquilino->nombre_completo : 'No',
                        ] : 'No',
                    ] : 'No',
                    'estado' =>  $socio->puesto ? $socio->puesto->estado:'No',
                    'fecha_registro' =>$socio->usuario ? $socio->usuario->fecha_registro : 'No',
                    'deuda' =>$socio->puesto && $socio->puesto->deuda ? $socio->puesto->deuda->total_deuda : 'No',
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
