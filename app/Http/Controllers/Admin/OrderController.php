<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('customer_name', 'like', "%{$s}%")
                ->orWhere('code', 'like', "%{$s}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pending = Order::where('status', 'pending')->count();

        return view('admin.orders', compact('orders', 'pending'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);

        DB::transaction(function () use ($order, $data) {
            // Stok berkurang sekali saja, saat pesanan sudah dibayar (Diproses/Dikirim/Selesai).
            if (in_array($data['status'], Order::PAID, true) && !$order->stock_deducted) {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->update(['stock' => max(0, $item->product->stock - $item->qty)]);
                    }
                }
                $order->stock_deducted = true;
            }
            $order->status = $data['status'];
            $order->save();
        });

        return back()->with('ok', 'Status diperbarui ✓');
    }
}

