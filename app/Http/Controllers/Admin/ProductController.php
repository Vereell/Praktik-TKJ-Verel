<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Http\Request;

/** Kelola stok & diskon tiap kombinasi varian x ukuran. */
class ProductController extends Controller
{
    public function stock()
    {
        return view('admin.stock', $this->matrix());
    }

    public function updateStock(Request $request, Product $product)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0|max:100000']);
        $product->update($data);

        return back()->with('ok', 'Stok berhasil diperbarui ✓');
    }

    public function discounts()
    {
        return view('admin.discounts', $this->matrix());
    }

    public function updateDiscount(Request $request, Product $product)
    {
        $data = $request->validate(['discount' => 'required|integer|min:0|max:80']);
        $product->update($data);

        return back()->with('ok', 'Diskon berhasil diperbarui ✓');
    }

    private function matrix(): array
    {
        return [
            'variants' => Variant::with('products')->orderBy('id')->get(),
            'sizes'    => Size::orderBy('ml')->get(),
        ];
    }
}
