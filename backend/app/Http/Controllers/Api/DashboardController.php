<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $todayTransactions = Transaction::whereDate('created_at', today());

        if ($request->user()->role === 'piket') {
            $todayTransactions->where('user_id', $request->user()->id);
        }

        return response()->json([
            'daily_revenue' => (float) $todayTransactions->sum('total_amount'),
            'total_transactions' => (clone $todayTransactions)->count(),
            'product_count' => Product::count(),
            'low_stock_count' => Product::where('stock', '<=', 5)->count(),
            'today_schedule' => Schedule::with('user:id,name,email,role')
                ->whereDate('date', today())
                ->when($request->user()->role === 'piket', fn ($query) => $query->where('user_id', $request->user()->id))
                ->get(),
        ]);
    }
}
