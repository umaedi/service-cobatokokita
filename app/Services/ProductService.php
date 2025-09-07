<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductService
{
    // Tambah produk baru
    public function addProduct($data)
    {
        try {
            if (isset($data['image']) && $data['image']->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($data['image']->getPathname());

                // resize + crop
                $image->cover(600, 600);

                // simpan sebagai webp
                $filename = uniqid() . '.webp';
                $path = 'images/products/' . $filename;
                $image->toWebp(60)->save(storage_path('app/public/' . $path));

                $data['image'] = $path;
            }

            Product::create([
                'branch_id'   => 1,
                'category_id' => $data['category_id'],
                'unit_id'     => $data['unit_id'],
                'name'        => $data['name'],
                'price_buy'   => $data['price_buy'],
                'price_sell'  => $data['price_sell'],
                'stock'       => $data['stock'] ?? 0,
                'image'       => $data['image'] ?? null,
            ]);

            return ['success' => true, 'message' => 'Item berhasil disimpan'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Ambil semua produk
    public function getProducts()
    {
        return Product::with(['category','unit'])->get();
    }

    // Update produk
    public function updateProduct($id, $data)
    {
        try {
            $product = Product::findOrFail($id);

            if (isset($data['image']) && $data['image']->isValid()) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($data['image']->getPathname());

                // resize + crop
                $image->cover(600, 600);

                // simpan sebagai webp
                $filename = uniqid() . '.webp';
                $path = 'images/products/' . $filename;
                $image->toWebp(60)->save(storage_path('app/public/' . $path));

                $data['image'] = $path;
            }

            $product->update($data);

            return ['success' => true, 'message' => 'Item berhasil diperbaharui'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Hapus produk
    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    // Tambah stok
    public function addStock($productId, $quantity = 1)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->increment('stock', $quantity);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Kurangi stok
    public function reduceStock($productId, $quantity = 1)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->decrement('stock', $quantity);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
