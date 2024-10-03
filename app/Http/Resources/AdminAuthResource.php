<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Return reponse if token exists 
        $data = $this->resource;
        if ($data["token"]) {
            return [
                'token' => $data["token"],
                'user' => [
                    'username' => $data['user']->username,
                    'email' => $data['user']->email,
                ]
            ];
        } else {
            return [
                'user' => [
                    'username' => $data['user']->username,
                    'email' => $data['user']->email,
                ]
            ];
        }
    }
}
