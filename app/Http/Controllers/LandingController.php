<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;

class LandingController extends Controller
{
    public function index()
    {
        $variants = Variant::orderBy('id')->get();
        $sizes = Size::orderBy('ml')->get();
        $products = Product::with('size')->get();

        // Data katalog untuk komponen Alpine di halaman.
        $catalog = [
            'variants' => $variants->map(fn ($v) => [
                'id' => $v->id, 'name' => $v->name, 'description' => $v->description,
                'emoji' => $v->emoji, 'img' => $v->img,
            ])->values(),
            'sizes' => $sizes->map(fn ($s) => ['id' => $s->id, 'label' => $s->label])->values(),
            'products' => $products->map(fn ($p) => [
                'id' => $p->id, 'variant_id' => $p->variant_id, 'size_id' => $p->size_id,
                'stock' => $p->stock, 'discount' => $p->discount,
                'base_price' => $p->size->base_price, 'price' => $p->final_price ?? $p->size->base_price,
            ])->values(),
        ];

        $stats = [
            'variants' => $variants->count(),
            'sizes'    => $sizes->count(),
            'orders'   => Order::whereIn('status', Order::PAID)->count(),
        ];

        return view('Landing', compact('catalog', 'stats'));
    }
}
