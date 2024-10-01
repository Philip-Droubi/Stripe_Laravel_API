<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "amount" => (int) $this->amount,
            "price" => (float) $this->price,
            "user" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
            ],
        ];
    }
}
