<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\DetallePagos;
use App\Models\Servicio;
use Carbon\Carbon;

class ReporteDeudaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($deuda) {
                    $mes = '';
                    $mesCarbon = (new Carbon( $deuda->fecha_registro ))->format('m');
                    switch($mesCarbon){
                        case '01': $mes = 'Enero';
                            break;
                        case '02': $mes = 'Febreo';
                            break;
                        case '03': $mes = 'Marzo';
                            break;
                        case '04': $mes = 'Marzo';
                            break;
                        case '05': $mes = 'Abril';
                            break;
                        case '06': $mes = 'Mayo';
                            break;
                        case '07': $mes = 'Junio';
                            break;
                        case '08': $mes = 'Julio';
                            break;
                        case '09': $mes = 'Agosto';
                            break;
                        case '10': $mes = 'Septiembre';
                            break;
                        case '11': $mes = 'Nobiembre';
                            break;
                        case '12': $mes = 'diciembre';
                            break;
                    }

                    $importeSuma = DetallePagos::where('id_deuda',$deuda->id_deuda)->sum('importe');
                    $importe_pagado = $importeSuma ? $importeSuma : 0;
                    $importe_por_pagar = $deuda->total_deuda - $importeSuma;

                    return [
                        'id_cuota' => $deuda->id_cuota,
                        'anio' => (new Carbon( $deuda->fecha_registro ))->format('Y'),
                        'mes' => $mes,
                        'servicio_descripcion' => Servicio::select('descripcion')->where('id_servicio',$deuda->id_servicio)->first()->descripcion,
                        'total' => $deuda->total_deuda,
                        'importe_pagado' => $importe_pagado,
                        'importe_por_pagar' => $importe_por_pagar,
                    ];
                    // --#ID CUOTA	Año	Mes	Desc. Servicios por Cuota	Total (S/)	Imp. Pagado (S/)	Imp. Por pagar (S/)
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
