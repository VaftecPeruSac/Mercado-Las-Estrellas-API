<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\CuotaServicios;

class CuotaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($cuota) {
                    
                    return [
                        'id_cuota' => $cuota->id_cuota,
                        'importe' => $cuota->importe,
                        'fecha_emision' => $cuota->fecha_emision,
                        'fecha_vencimiento' => $cuota->fecha_vencimiento,
                        'global' => $cuota->global ? 'Sí' : 'No',
                        // Si la cuota es global, no se mostrarán los puestos asignados
                        // 'puestos_asignados' => $cuota->global ? null : $cuota->puestosCuota->map(function ($puesto) {
                        //     return [
                        //         'id_puesto' => $puesto->puesto->id_puesto,
                        //         'numero' => $puesto->puesto->numero_puesto,
                        //     ];
                        // }),
                        'puestos_asignados' => $cuota->global ? null : CuotaServicios::select('puestos.*')
                            ->join('deuda_cuotas','cuota_servicios.id_cuota_servicio','deuda_cuotas.id_cuota_servicio')
                            ->join('deudas','deuda_cuotas.id_deuda','deudas.id_deuda')
                            ->join('puestos','deudas.id_puesto','puestos.id_puesto')
                            ->where('cuota_servicios.id_cuota',$cuota->id_cuota)
                            ->get()->map(function ($puesto) {
                                return [
                                    'id_puesto' => $puesto->id_puesto,
                                    'numero' => $puesto->numero_puesto,
                                ];
                            }),
                        'servicios' => $cuota->cuotaServicios->map(function ($servicio) {
                            return [
                                'id_servicio' => $servicio->servicio->id_servicio,
                                'nombre' => $servicio->servicio->nombre,
                                'costo_unitario' => $servicio->servicio->costo_unitario,
                            ];
                        }),
                    ];
                }),
                'links' => [
                    'self' => url('/cuotas'),
                ],
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
