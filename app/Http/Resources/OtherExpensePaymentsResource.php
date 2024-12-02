<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherExpensePaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'expense_id' => $this->expense_id,
            'expense_category_name' => $this->expense_category_name,
            'expense_date' => $this->expense_date,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'expense_status' => $this->expense_status == 1 ? "completed" : "pending",
            'payment_reciept' => $this->payment_reciept,
            'payment_amount' => $this->payment_amount,
            'due_date' => $this->due_date,
        ];
    }
}
