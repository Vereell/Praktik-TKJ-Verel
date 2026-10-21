<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('ml')->get();

        return view('admin.sizes', compact('sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:30',
            'ml'         => 'required|integer|min:1|max:100000',
            'base_price' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $size = Size::create($data);
            foreach (Variant::pluck('id') as $variantId) {
                Product::create(['variant_id' => $variantId, 'size_id' => $size->id]);
            }
        });

        return back()->with('ok', "Ukuran \"{$data['label']}\" berhasil ditambahkan ✓");
    }

    public function update(Request $request, Size $size)
    {
        $data = $request->validate(['base_price' => 'required|integer|min:0']);
        $size->update($data);

        return back()->with('ok', 'Harga dasar diperbarui ✓');
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return back()->with('ok', "Ukuran \"{$size->label}\" dihapus");
    }
}
