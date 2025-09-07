<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "product_id"   => $this->product_id,
            "product_name"   => $this->product->name,
            "product_price_sell"   => $this->product->price_sell,
            "product_image_url" => $this->product->image ? asset('storage/'.$this->product->image) : null,
            "quantity"   => $this->quantity,
            "total"   => $this->total
        ];
    }
}
