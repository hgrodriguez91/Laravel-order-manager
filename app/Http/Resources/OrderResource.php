<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total' => $this->total()->value(),
            'confirmed' => $this->isConfirmed(),
            'items_count' => count($this->items()),
        ];
    }
}
