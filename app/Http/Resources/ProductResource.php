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
            "id"            => $this->id,
            "branch_id"            => $this->branch_id,
            "category_id"   => $this->category?->id,
            "category_name" => $this->category?->name,
            "unit_id"       => $this->unit?->id,
            "unit_name"     => $this->unit?->name,
            "name"          => $this->name,
            "price_buy"     => $this->price_buy,
            "price_sell"    => $this->price_sell,
            "stock"         => $this->stock,
            "image_url" => $this->image ? asset('storage/'.$this->image) : null,
        ];
    }
}
