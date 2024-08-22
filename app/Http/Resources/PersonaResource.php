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
            'nombre' => $this->nombre,
            'apellido_paterno' => $this->apellido_paterno,
            'apellido_materno' => $this->apellido_materno,
            'dni' => $this->dni,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'direccion'=> $this->direccion,
            'sexo'=> $this->sexo,
            'estado' => $this->estado,
            'fecha_registro' => $this->fecha_registro,
        ];
    }
}
