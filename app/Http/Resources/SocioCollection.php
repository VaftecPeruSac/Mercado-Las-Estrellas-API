<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

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
                $query = DB::select("select sum(b.total_deuda) deuda from puestos a
                    left join deudas b on a.id_puesto = b.id_puesto
                    where a.id_socio = ".$socio->id_socio);
                $deuda = collect($query)->first();
                $deuda_total = $deuda->deuda ? $deuda->deuda : 0;
                $query = DB::select("select sum(importe) pago from puestos a left join detalle_pagos b on a.id_puesto = b.id_puesto
                    where a.id_socio = ".$socio->id_socio);
                $pago = collect($query)->first();
                $pago_total = $pago->pago ? $pago->pago : 0;
                $deuda = $deuda_total - $pago_total;
                return [
                    'id_socio' => $socio->id_socio,
                    'nombre_completo' => $socio->usuario->nombre_usuario,
                    'nombre_socio' => $socio->nombres,
                    'apellido_paterno' => $socio->apellido_paterno,
                    'apellido_materno' => $socio->apellido_materno,
                    'dni' => $socio->dni ? $socio->dni : 'No',
                    'sexo' => $socio->sexo,
                    'direccion' => $socio->direccion ? $socio->direccion : 'No',
                    'telefono' => $socio->telefono ? $socio->telefono : 'No',
                    'correo' => $socio->correo ? $socio->correo : 'No',
                    'id_puesto' => $socio->puesto ? $socio->puesto->id_puesto : '',
                    'numero_puesto' => $socio->puesto ? $socio->puesto->numero_puesto : 'No asignado',
                    'id_block' => $socio->puesto ? $socio->puesto->block->id_block : '',
                    'block_nombre' => $socio->puesto ? $socio->puesto->block->nombre : 'No asignado',
                    'gironegocio_nombre' => $socio->puesto && $socio->puesto->gironegocio ? $socio->puesto->gironegocio->nombre : 'No asignado',
                    'nombre_inquilino' =>$socio->puesto && $socio->puesto->inquilino ? $socio->puesto->inquilino->nombre_completo : 'No asignado',
                    'estado' =>  $socio->usuario->estado,
                    'fecha_registro' => $socio->fecha_registro ? $socio->fecha_registro : null,
                    'deuda' =>$deuda,
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
