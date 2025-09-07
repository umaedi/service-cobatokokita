<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // buat transaksi baru
    public function store(Request $request)
    {
        $cartItems = $request->cart_items ?? [];

        return response()->json($this->transactionService->createTransaction($cartItems));
    }

    // ambil semua transaksi
    public function index()
    {
        $response = $this->transactionService->getTransactions();
        return TransactionResource::collection($response);
    }
}
