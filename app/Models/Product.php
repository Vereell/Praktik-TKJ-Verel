<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

/** Kombinasi varian (rasa) x ukuran botol. */
class Product extends Model
{
    protected $fillable = ['variant_id', 'size_id', 'stock', 'discount'];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getFinalPriceAttribute(): int
    {
        return (int) round($this->size->base_price * (100 - $this->discount) / 100);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->variant->name} {$this->size->label}";
    }
}
