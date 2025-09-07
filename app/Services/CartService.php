<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    // Tambah ke cart
    public function addToCart($productId, $quantity = 1)
    {
        try {
            return DB::transaction(function () use ($productId, $quantity) {
                // ambil data produk dari DB
                $product = Product::findOrFail($productId);
                $price   = $product->price_sell; // atau price_buy, sesuai kebutuhan

                $cartItem = Cart::where('product_id', $productId)->first();

                if ($cartItem) {
                    // update quantity & total
                    $newQuantity = $cartItem->quantity + $quantity;
                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'total'    => $newQuantity * $price,
                    ]);
                } else {
                    // insert baru
                    Cart::create([
                        'branch_id' => 1,
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'total'      => $price * $quantity,
                    ]);
                }

                // update stok produk
                $product->decrement('stock', $quantity);

                return [
                    'success' => true,
                    'message' => 'Item berhasil ditambahkan ke dalam keranjang',
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan item ke dalam keranjang',
                'error'   => $e->getMessage(),
            ];
        }
    }


    // Ambil semua cart
    public function getCart()
    {
        $data = Cart::with('product:id,name,price_sell,image,stock')->get();
        return $data;
        
    }

    // Tambah quantity
    public function addQuantity($productId, $quantity = 1)
    {
        try {
            return DB::transaction(function () use ($productId, $quantity) {
                $cart = Cart::where('product_id', $productId)->first();
                $product = Product::find($productId);

                if (!$cart || !$product) {
                    return [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan di cart atau database!',
                    ];
                }

                if ($quantity <= 0) {
                    // hapus item dari cart
                    $cart->delete();
                } else {
                    // hitung quantity baru
                    $newQuantity = $cart->quantity + $quantity;

                    $cart->update([
                        'quantity' => $newQuantity,
                        'total'    => $newQuantity * $product->price_sell, 
                    ]);
                }

                // update stok produk (kurangi sesuai jumlah ditambahkan)
                $product->decrement('stock', $quantity);

                return [
                    'success' => true,
                    'message' => 'Item berhasil diperbarui',
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate jumlah item: ' . $e->getMessage(),
            ];
        }
    }


    // Kurangi quantity
    public function reduceQuantity($productId, $quantity = 1)
    {
        try {
            return DB::transaction(function () use ($productId, $quantity) {
                $cart = Cart::where('product_id', $productId)->first();
                $product = Product::find($productId);

                if (!$cart || !$product) {
                    return [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan di cart atau database!',
                    ];
                }

                // hitung quantity baru
                $newQuantity = $cart->quantity - $quantity;

                if ($newQuantity <= 0) {
                    // kalau quantity habis / minus → hapus item
                    $cart->delete();

                    // kembalikan semua stok ke produk
                    $product->increment('stock', $cart->quantity);
                } else {
                    // update quantity & total
                    $cart->update([
                        'quantity' => $newQuantity,
                        'total'    => $newQuantity * $product->price_sell,
                    ]);

                    // kembalikan stok produk sesuai jumlah dikurangi
                    $product->increment('stock', $quantity);
                }

                return [
                    'success' => true,
                    'message' => 'Item berhasil dikurangi',
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengurangi item: ' . $e->getMessage(),
            ];
        }
    }


    // Hapus 1 item dari cart
    public function removeFromCart($productId)
    {
        try {
            return DB::transaction(function () use ($productId) {
                $product = Product::find($productId);
                $cart = Cart::where('branch_id', 1)->where('product_id', $productId)->first();
                $cart->delete();

                Product::where('id', $productId)->update([
                    'stock' => $product->stock + $cart->quantity,
                ]);

                return [
                    'success' => true,
                    'message' => 'Item berhasil dihapus',
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item!',
            ];
        }
    }

    // Hapus semua quantity dari 1 produk
    public function removeAllFromCart($productId)
    {
        try {
            return DB::transaction(function () use ($productId) {
                $cartItem = Cart::where('product_id', $productId)->first();

                if ($cartItem) {
                    Product::where('id', $productId)->increment('stock', $cartItem->quantity);
                }

                Cart::where('product_id', $productId)->delete();

                return true;
            });
        } catch (\Exception $e) {
            return false;
        }
    }

    // Kosongkan seluruh cart
    public function clearCart()
    {
        try {
            Cart::truncate();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
