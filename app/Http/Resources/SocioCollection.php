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
                    // 'nombre_completo' => $socio->usuario ? $socio->usuario->nombre_usuario : '',
                    'nombre_completo' => $socio->persona ? $socio->persona->nombre_completo : '',
                    'datos_socio' => $socio->persona ? [
                        'nombre_socio' => $socio->persona->nombre,
                        'apellido_paterno' => $socio->apellido_paterno,
                        'apellido_materno' => $socio->apellido_materno,
                        'dni' => $socio->persona->dni ? $socio->persona->dni : 'No',
                        'sexo' => $socio->persona->sexo,
                        'direccion' => $socio->persona->direccion ? $socio->persona->direccion : 'No',
                        'telefono' => $socio->persona->telefono ? $socio->persona->telefono : 'No',
                        'correo' => $socio->persona->correo ? $socio->persona->correo : 'No',
                    ] : 'No',
                    'puesto' => $socio->puesto ? [
                        'id_puesto' => $socio->puesto->id_puesto,
                        'numero_puesto' => $socio->puesto->numero_puesto,
                        'bloque' => $socio->puesto->block,
                        'gironegocio_nombre' => $socio->puesto && $socio->puesto->gironegocio ? $socio->puesto->gironegocio->nombre : 'No',
                        'inquilino' =>  $socio->puesto->inquilino ? [
                            'id_inquilino' => $socio->puesto->id_inquilino,
                            'nombre_inquilino' =>$socio->puesto->inquilino->nombre_completo ? $socio->puesto->inquilino->nombre_completo : 'No',
                        ] : 'No',
                    ] : 'No',
                    'estado' =>  $socio->puesto ? $socio->puesto->estado:'No',
                    'fecha_registro' => $socio->fecha_registro ? $socio->fecha_registro : null,
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
