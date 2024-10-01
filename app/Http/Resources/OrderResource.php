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
        $data = [
            "id" => $this->id,
            "total_price" => (float) $this->total_price,
            "products_count" => (int) $this->products_count,
            "is_done" => (bool) $this->is_done,
            "user" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
            ],
        ];

        $products = [];
        foreach ($this->products as $product) {
            $products[] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => (float) $this->price,
                "amount" => (int) $product->pivot->amount,
            ];
        }
        $data["products"] = $products;
        return $data;
    }
}
