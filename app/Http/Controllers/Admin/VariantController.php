<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariantController extends Controller
{
    public function index()
    {
        $variants = Variant::with('products')->orderBy('id')->get();

        return view('admin.variants', compact('variants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:60',
            'emoji'       => 'nullable|string|max:8',
            'description' => 'nullable|string|max:300',
            'img'         => 'nullable|url|max:500',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['img'] = asset('storage/' . $request->file('image')->store('variants', 'public'));
        }
        if (empty($data['img'])) {
            return back()->withErrors(['img' => 'Isi URL gambar atau unggah file gambar.'])->withInput();
        }
        unset($data['image']);
        $data['emoji'] = $data['emoji'] ?: '🥛';

        DB::transaction(function () use ($data) {
            $variant = Variant::create($data);
            foreach (Size::pluck('id') as $sizeId) {
                Product::create(['variant_id' => $variant->id, 'size_id' => $sizeId]);
            }
        });

        return back()->with('ok', "Varian \"{$data['name']}\" berhasil ditambahkan ✓");
    }

    public function destroy(Variant $variant)
    {
        $variant->delete();

        return back()->with('ok', "Varian \"{$variant->name}\" dihapus");
    }
}
