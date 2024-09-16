<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlockCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
            return [
                'data' => $this->collection->transform(function ($block) {
                    
                    return [
                        'id_block' => $block->id_block,
                        'nombre' => $block->nombre,
                    ];
                }),
                'links' => [
                    'self' => url('/blocks'),
                ],  
                'meta' => [
                    'total' => $this->collection->count(),
                ],
            ];
        }
}
