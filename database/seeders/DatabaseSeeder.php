<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yoghurtq.id'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );

        // Data contoh dari desain — ubah lewat dashboard admin.
        $u = fn ($id) => "https://images.unsplash.com/{$id}?w=600&h=600&fit=crop&auto=format";
        $variants = [
            ['Original', '🥛', 'Pure creamy yoghurt, no additives — classic and timeless.', $u('photo-1488477181946-6428a0291777')],
            ['Blueberry', '🫐', 'Wild blueberry compote swirled through silky yoghurt.', $u('photo-1559181567-c3190ca9be46')],
            ['Strawberry', '🍓', 'Sun-ripened strawberry purée with a fresh, bright finish.', $u('photo-1488900128323-21503983a07e')],
            ['Mango', '🥭', 'Tropical Harum Manis mango with a luscious golden swirl.', $u('photo-1553279768-865429fa0078')],
            ['Honey & Mint', '🍃', 'Wildflower honey with cool spearmint — distinctively refreshing.', $u('photo-1571167530149-c1105da4702b')],
            ['Greek Style', '🏺', 'Triple-strained, high-protein, authentically thick.', $u('photo-1563636619-e9143da7973b')],
        ];
        $sizes = [['150 ml', 150, 15000], ['200 ml', 200, 20000], ['350 ml', 350, 32000], ['500 ml', 500, 45000], ['1000 ml', 1000, 85000]];
        $stock = [
            [240, 180, 120, 80, 40], [180, 150, 90, 60, 30], [160, 120, 80, 50, 20],
            [140, 110, 70, 45, 25], [120, 100, 60, 40, 18], [200, 160, 100, 70, 35],
        ];
        $discount = ['0-4' => 10, '5-3' => 15, '1-2' => 5];

        $sizeModels = collect($sizes)->map(fn ($s) => Size::create(['label' => $s[0], 'ml' => $s[1], 'base_price' => $s[2]]));

        foreach ($variants as $vi => $v) {
            $variant = Variant::create(['name' => $v[0], 'emoji' => $v[1], 'description' => $v[2], 'img' => $v[3]]);
            foreach ($sizeModels as $si => $size) {
                Product::create([
                    'variant_id' => $variant->id,
                    'size_id'    => $size->id,
                    'stock'      => $stock[$vi][$si],
                    'discount'   => $discount["{$vi}-{$si}"] ?? 0,
                ]);
            }
        }
    }
}
