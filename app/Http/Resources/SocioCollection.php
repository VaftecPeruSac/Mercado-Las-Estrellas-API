<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Puesto;

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
                $puestos = Puesto::where('id_puesto',$socio->id_puesto)->get();

                $deuda = 0;
                if($socio->id_puesto) {
                    $query = DB::select("select sum(total_deuda) deuda
                        from deudas where id_puesto = ".$socio->id_puesto);
                    $deudaSum = collect($query)->first();
                    $deuda_total = $deudaSum->deuda ? $deudaSum->deuda : 0;

                    $query = DB::select("select sum(importe) pago from detalle_pagos
                        where id_puesto = ".$socio->id_puesto);
                    $pago = collect($query)->first();
                    $pago_total = $pago->pago ? $pago->pago : 0;
                    $deudaTotal = $deuda_total - $pago_total;

                    if((float)$deudaTotal == 0) {
                        $deuda = 0;
                    } else {
                        $deuda = number_format((float)$deudaTotal, 2, '.', "");
                    }
                }

                return [
                    'id_socio' => $socio->id_socio,
                    'nombre_completo' => $socio->persona ? $socio->persona->nombre_completo : 'no',
                    'nombre_socio' => $socio->persona ? $socio->persona->nombre : 'no',
                    'apellido_paterno' => $socio->persona ? $socio->persona->apellido_paterno : 'no',
                    'apellido_materno' => $socio->persona ? $socio->persona->apellido_materno : 'no',
                    'dni' => $socio->persona ? $socio->persona->dni : 'No',
                    'sexo' => $socio->persona ? $socio->persona->sexo : 'No',
                    'direccion' => $socio->persona ? $socio->persona->direccion : 'No',
                    'telefono' => $socio->persona ? $socio->persona->telefono : 'No',
                    'correo' => $socio->persona ? $socio->persona->correo : 'No',
                    'puestos' => $puestos->map(function ($puesto) {
                        return [
                            'id_puesto' => $puesto->id_puesto,
                            'numero_puesto' => $puesto->numero_puesto,
                            'block' => $puesto->block,
                            'gironegocio' => $puesto->gironegocio,
                            'nombre_inquilino' => $puesto->inquilino ? $puesto->inquilino->nombre.' '.$puesto->inquilino->apellido_paterno.' '.$puesto->inquilino->apellido_materno : 'No asignado',
                        ];
                    }),
                    'estado' =>  $socio->usuario ? $socio->usuario->estado : '0',
                    'fecha_registro' => $socio->fecha_registro ? $socio->fecha_registro : null,
                    'deuda' => $deuda,
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
