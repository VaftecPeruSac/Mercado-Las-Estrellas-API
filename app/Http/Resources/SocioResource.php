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
    {//resource tiene los datos que jalan en otras tablas
        return [
            'id_socio'=>$this->id_socio,
            'id_usuario'=>$this->id_usuario,
            'saldo' => $this->saldo, 
            'fecha_registro'=>$this->fecha_registro,
            'usuario' => new UsuarioResource($this->usuario), // Si estás incluyendo sociosc
        ];
    }
}
