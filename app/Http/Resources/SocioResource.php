<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_socio'=>$this->id_socio,
            'correo'=>$this->correo,
            'telefono' => $this->telefono, 
            'id_persona'=>$this->id_persona,
            'fecha_registro'=>$this->fecha_registro,
            'estado'=>$this->estado,
        ];
    }
}
