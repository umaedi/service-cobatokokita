<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "branch_id"     => $this->branch_id,
            "quantity"   => $this->quantity,
            "total"   => $this->total,
            "product_name"  => $this->product->name,
            "product_price_sell"  => $this->product->price_sell,
            "image_url" => $this->product->image ? asset('storage/'.$this->product->image) : null,
        ];
    }
}
