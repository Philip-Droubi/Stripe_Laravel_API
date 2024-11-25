<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "total_price" => (float) $this->total_price,
            "products_count" => (int) $this->products_count,
            "status" => $this->status,
            "user" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
            ],
            "products" => $this->products->map(function ($product): array {
                return [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => (float) $product->price,
                    "amount" => (int) $product->pivot->amount,
                ];
            })->toArray(),
        ];
    }
}
