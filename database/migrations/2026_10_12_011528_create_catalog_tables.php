<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Varian = rasa (Original, Blueberry, dst.)
        Schema::create('variants', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('description')->nullable();
            $t->string('emoji')->default('🥛');
            $t->string('img', 500)->nullable();
            $t->timestamps();
        });

        // Ukuran botol
        Schema::create('sizes', function (Blueprint $t) {
            $t->id();
            $t->string('label');
            $t->unsignedInteger('ml');
            $t->unsignedInteger('base_price');
            $t->timestamps();
        });

        // Produk = kombinasi varian x ukuran (punya stok & diskon sendiri)
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('variant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('size_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('stock')->default(0);
            $t->unsignedTinyInteger('discount')->default(0);
            $t->timestamps();
            $t->unique(['variant_id', 'size_id']);
        });

        // Pesanan (regular atau pre-order)
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique(); // Kode pesanan
            $t->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('customer_name');
            $t->string('phone');
            $t->text('address');
            $t->text('note')->nullable();
            $t->enum('type', ['regular', 'preorder'])->default('regular');
            $t->timestamp('pickup_date')->nullable(); // Untuk pre-order
            $t->enum('status', ['pending', 'awaiting_payment', 'processing', 'shipped', 'delivered', 'out_of_stock', 'cancelled'])->default('pending');
            $t->unsignedInteger('total')->default(0); // Total harga pesanan
            $t->boolean('stock_deducted')->default(false);
            $t->timestamps();
        });

        // Item dalam pesanan
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name');  // label produk saat dipesan
            $t->unsignedInteger('qty');
            $t->unsignedInteger('price'); // Harga saat dipesan
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('variants');
    }
};
