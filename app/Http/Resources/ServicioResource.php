<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{

    public function toArray(Request $request): array
    {//resource tiene los datos que jalan en otras tablas
        return [
            'descripcion'=>$this->descripcion,
            'costo_unitario' => $this->costo_unitario, 
            'tipo_servicio' => $this->tipo_servicio, 
            'estado' => $this->estado, 
            'fecha_registro'=>$this->fecha_registro,
        ];
    }
}
