<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_socio' => $this->id_socio,
            'id_documento' => $this->id_documento,
            'numero_pago' => $this->numero_pago,
            'serie' => $this->serie,
            'total_pago' => $this->total_pago,
            'fecha_registro' => $this->fecha_registro,
        ];
    }
}
