<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Menerima pesanan (keranjang / pre-order) dari landing page. */
class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'     => 'required|string|max:100',
            'phone'             => 'required|string|max:20',
            'address'           => 'required|string|max:500',
            'note'              => 'nullable|string|max:500',
            'type'              => 'required|in:regular,preorder',
            'pickup_date'       => 'required_if:type,preorder|nullable|date|after_or_equal:today',
            'items'             => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty'       => 'required|integer|min:1|max:999',
        ]);

        // Gabungkan baris dengan produk yang sama.
        $qtys = [];
        foreach ($data['items'] as $row) {
            $qtys[$row['product_id']] = ($qtys[$row['product_id']] ?? 0) + (int) $row['qty'];
        }
        $products = Product::with(['variant', 'size'])->whereIn('id', array_keys($qtys))->get();

        $order = DB::transaction(function () use ($data, $qtys, $products) {
            $code = 'QY-' . strtoupper(bin2hex(random_bytes(3))) . rand(10, 99);
            $order = Order::create([
                'code'          => $code,
                'customer_name' => $data['customer_name'],
                'phone'         => $data['phone'],
                'address'       => $data['address'],
                'note'          => $data['note'] ?? null,
                'type'          => $data['type'],
                'pickup_date'   => $data['type'] === 'preorder' ? $data['pickup_date'] : null,
                'status'        => 'pending',
            ]);

            $total = 0;
            foreach ($products as $p) {
                $qty = $qtys[$p->id];
                $order->items()->create([
                    'product_id' => $p->id,
                    'name'       => $p->label,
                    'qty'        => $qty,
                    'price'      => $p->final_price,
                ]);
                $total += $p->final_price * $qty;
            }

            $order->update(['code' => 'QY-' . (10284 + $order->id), 'total' => $total]);

            return $order->fresh('items');
        });

        return response()->json([
            'code'   => $order->code,
            'total'  => $order->total,
            'wa_url' => $this->waUrl($order),
        ]);
    }

    private function waUrl(Order $order): string
    {
        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $lines = $order->items->map(fn ($i) => "• {$i->name} × {$i->qty} = " . $rp($i->price * $i->qty))->implode("\n");
        $shop = config('yoghurtq.name');

        if ($order->is_preorder) {
            $text = "Halo {$shop}! 🙌\n\nSaya ingin melakukan *Pre-Order* (#{$order->code}):\n{$lines}\n"
                . "\nTanggal pengambilan: " . $order->pickup_date->format('d/m/Y')
                . "\n*Total: " . $rp($order->total) . "*";
        } else {
            $text = "Halo {$shop}! 👋\n\nSaya ingin memesan (#{$order->code}):\n{$lines}\n\n*Total: " . $rp($order->total) . '*';
        }
        $text .= "\n\nNama: {$order->customer_name}\nNo. HP: {$order->phone}\nAlamat: {$order->address}"
            . ($order->note ? "\nCatatan: {$order->note}" : '')
            . "\n\nApakah stoknya tersedia? Terima kasih 🙏";

        $adminWa = preg_replace('/\D+/', '', (string) config('yoghurtq.wa_number', '6285722281849'));
        if (str_starts_with($adminWa, '0')) {
            $adminWa = '62' . substr($adminWa, 1);
        }

        return 'https://wa.me/' . $adminWa . '?text=' . rawurlencode($text);
    }
}