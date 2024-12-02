<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryPaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'inventory_payment_id' => $this->inventory_payment_id,
            'inventory_id' => $this->inventory_id,
            'equipment_name' => $this->equipment_name,
            'total_price' => $this->total_price,
            'payment_date' => $this->payment_date,
            'payment_amount' => $this->payment_amount,
            'amount_paid' => $this->amount_paid,
            'due_amount' => $this->due_amount,
            'balance' => $this->balance,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status == 1 ? "completed" : "pending",
            'payment_reciept' => $this->payment_reciept,

        ];
    }
}
