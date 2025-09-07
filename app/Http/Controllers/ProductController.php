<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getProducts();
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $result = $this->productService->addProduct($request->all());
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = $this->productService->updateProduct($id, $request->all());
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = $this->productService->deleteProduct($id);
        return response()->json(['success' => $result]);
    }
}
