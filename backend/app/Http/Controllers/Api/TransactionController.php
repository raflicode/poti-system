<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user:id,name,email,role', 'items.product:id,name'])
            ->when($request->user()->role === 'piket', fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest()
            ->paginate((int) $request->query('per_page', 10));

        return response()->json($transactions);
    }

    public function show(Request $request, Transaction $transaction)
    {
        if ($request->user()->role === 'piket' && $transaction->user_id !== $request->user()->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return response()->json($transaction->load(['user:id,name,email,role', 'items.product:id,name']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($data, $request) {
            $products = Product::whereIn('id', collect($data['items'])->pluck('product_id'))->lockForUpdate()->get()->keyBy('id');
            $total = 0;
            $items = [];

            foreach ($data['items'] as $item) {
                $product = $products[$item['product_id']];

                if ($product->status !== 'active' || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ["Stok {$product->name} tidak cukup."],
                    ]);
                }

                $subtotal = (float) $product->price * $item['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            if ($data['paid_amount'] < $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => ['Nominal bayar kurang dari total transaksi.'],
                ]);
            }

            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total_amount' => $total,
                'paid_amount' => $data['paid_amount'],
                'change_amount' => $data['paid_amount'] - $total,
            ]);

            foreach ($items as $item) {
                $transaction->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
                $item['product']->refresh()->refreshStatus();
            }

            return response()->json($transaction->load(['user:id,name,email,role', 'items.product:id,name']), 201);
        });
    }
}
