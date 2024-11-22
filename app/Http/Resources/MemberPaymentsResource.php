<?php

namespace App\Http\Resources;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberPaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $membership = Membership::getMembershipById($this->membership_id);
        $membershipData = !empty($membership) ? $membership[0] : null;

        return [
            'member_payment_id' => $this->member_payment_id,
            'member_id' => $this->member_id,
            'member_name' => $this->member_name,
            'payment_date' => $this->payment_date,
            'payment_amount' => $this->payment_amount,
            'paid_amount' => $this->paid_amount,
            'dues' => $this->dues,
            'balance' => $this->balance,
            'membership' => $membershipData->membership_type ?? null,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status == 1 ? "completed" : "pending",
            'payment_reciept' => $this->payment_reciept,

        ];
    }
}
