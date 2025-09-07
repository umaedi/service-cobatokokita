<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    // Buat transaksi baru
    public function createTransaction($cartItems)
    {
        try {
            return DB::transaction(function () use ($cartItems) {
                // hitung total transaksi
                $total = collect($cartItems)->reduce(function ($carry, $item) {
                    return $carry + ((float)$item['product_price_sell'] * (int)$item['quantity']);
                }, 0);

                // simpan detail transaksi
                foreach ($cartItems as $item) {
                    Transaction::create([
                        'branch_id' => 1,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'total'      => $total,
                        'date'       => now(),
                    ]);
                }

                // kosongkan cart setelah transaksi
                Cart::where('branch_id', 1)->truncate();

                return ['success' => true, 'message' => 'Transaksi berhasil dibuat'];
            });
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Gagal membuat transaksi: ' . $e->getMessage()];
        }
    }

    // Ambil semua transaksi (dengan produk)
    public function getTransactions()
    {
        try {
            $transactions = Transaction::with('product:id,name,price_sell,image')
                ->orderBy('date', 'desc')
                ->get();

            return $transactions;
        } catch (\Exception $e) {
            return [];
        }
    }

}
