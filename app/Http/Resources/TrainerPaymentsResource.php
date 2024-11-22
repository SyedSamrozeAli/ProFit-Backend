<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerPaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'trainer_payment_id' => $this->trainer_payment_id,
            'trainer_id' => $this->trainer_id,
            'trainer_name' => $this->trainer_name,
            'payment_date' => $this->payment_date,
            'payment_amount' => $this->payment_amount,
            'paid_amount' => $this->paid_amount,
            'dues' => $this->dues,
            'balance' => $this->balance,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status == 1 ? "completed" : "pending",
            'payment_reciept'=>$this->payment_reciept,

        ];
    }
}
