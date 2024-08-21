<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PuestoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_puesto'=> $this->id_puesto,
            'id_socio'=>$this->id_socio,
            'id_gironegocio'=>$this->id_gironegocio,
            'id_block'=>$this->id_block,
            'area'=>$this->area,
            'id_inquilino'=>$this->id_inquilino,
            'estado'=>$this->estado,
            'fecha_registro'=>$this->fecha_registro,
        ];
    }
}
