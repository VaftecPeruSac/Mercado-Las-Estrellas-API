<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PagoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($pago) {
                    
                    return [
                        'id_pago' => $pago->id_pago, 
                        'puesto' => $pago->socio && $pago->socio->puesto  ?  $pago->socio->puesto->numero_puesto : 'no',
                        'socio' => $pago->socio && $pago->socio->usuario && $pago->socio->usuario->persona ? $pago->socio->usuario->persona->nombre : 'no',
                        'dni' => $pago->socio && $pago->socio->usuario && $pago->socio->usuario->persona ? $pago->socio->usuario->persona->dni : 'no',
                        'telefono' => $pago->socio && $pago->socio->usuario && $pago->socio->usuario->persona ? $pago->socio->usuario->persona->telefono : 'no',
                        'correo' => $pago->socio && $pago->socio->usuario && $pago->socio->usuario->persona ? $pago->socio->usuario->persona->correo : 'no',
                        // 'id_documento' => $pago->id_documento,
                        // 'numero_pago' => $pago->numero_pago,
                        // 'serie' => $pago->serie,
                        'total_pago' => $pago->total_pago,
                        'total_deuda' => $pago->socio && $pago->socio->deuda ? $pago->socio->deuda->total_deuda : 'no',
                        'fecha_registro' => $pago->fecha_registro,
                    ];
                }),
                'links' => [
                    'self' => url('/pagos'),
                ],
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
