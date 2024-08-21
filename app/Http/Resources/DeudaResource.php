<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeudaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_deuda'=>$this->id_deuda,
            'id_socio'=>$this->id_socio,
            'id_cuota'=>$this->id_cuota,
            'total_deuda'=>$this->total_deuda,
            'fecha_registro'=>$this->fecha_registro,
        ];
    }
}
