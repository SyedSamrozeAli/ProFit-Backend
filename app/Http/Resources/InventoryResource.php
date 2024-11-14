<?php

namespace App\Http\Resources;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $equipmentData = EquipmentResource::make(Equipment::getEquipment($this->equipment_id));

        return [
            'inventory_id' => $this->inventory_id,
            'equipment' => $equipmentData,
            'cost_per_unit' => $this->cost_per_unit,
            'purchase_date' => $this->purchase_date,
            'supplier_name' => $this->supplier_name,
            'warranty_period' => $this->warranty_period,
        ];
    }
}
