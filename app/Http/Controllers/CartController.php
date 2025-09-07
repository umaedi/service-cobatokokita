<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $carts = $this->cartService->getCart();
        return CartResource::collection($carts);
    }

    public function store(Request $request)
    {
        $response = $this->cartService->addToCart($request->product_id, $request->quantity);
        return response()->json($response);
    }

    public function addQty(Request $request)
    {
        $response = $this->cartService->addQuantity($request->product_id, $request->quantity);
        return response()->json($response);
    }

    public function reduceQty(Request $request)
    {
        $response = $this->cartService->reduceQuantity($request->product_id, $request->quantity);
        return response()->json($response);
    }

    public function destroy($productId)
    {
        return response()->json($this->cartService->removeFromCart($productId));
    }

    public function clear()
    {
        return response()->json(['success' => $this->cartService->clearCart()]);
    }
}
