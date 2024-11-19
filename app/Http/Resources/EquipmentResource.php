<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'equipment_id' => $this->equipment_id,
            'equipment_name' => $this->equipment_name,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->equipment_status,
            'description' => $this->description
        ];
    }
}
