<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;

class DashboardController extends Controller
{
    public function index()
    {
        $from = now()->subDays(29)->startOfDay();

        $daily = Order::whereIn('status', Order::PAID)
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as d, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $chart = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $from->copy()->addDays($i);
            $row = $daily[$day->toDateString()] ?? null;
            $chart[] = [
                'label'   => $day->format('d/m'),
                'revenue' => (int) ($row->revenue ?? 0),
                'orders'  => (int) ($row->orders ?? 0),
            ];
        }

        $stats = [
            'revenue'   => (int) Order::whereIn('status', Order::PAID)->where('created_at', '>=', $from)->sum('total'),
            'pending'   => Order::where('status', 'pending')->count(),
            'stock'     => (int) Product::sum('stock'),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 30)->count(),
        ];

        $variantStock = Variant::with('products')->orderBy('id')->get();
        $recent = Order::latest()->limit(5)->get();

        return view('admin.dashboard', compact('chart', 'stats', 'variantStock', 'recent'));
    }
}
