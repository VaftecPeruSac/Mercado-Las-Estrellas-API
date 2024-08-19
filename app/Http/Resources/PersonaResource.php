<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_persona'  => $this->id_persona,
            'nombre'=>$this->nombre,
            // 'nombre_completo'=>$this->nombre+' '+$this->apellidoP+" "+$this->apellidoM,
            'apellidoP'=>$this->apellidoP,
            'apellidoM'=>$this->apellidoM,
            'dni'=>$this->dni,
            'estado'=>$this->estado,
            // 'socio' => SocioResource::collection($this->whenLoaded('socio')) 
        ];
    }
}
