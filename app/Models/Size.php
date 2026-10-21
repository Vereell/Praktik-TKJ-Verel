<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['label', 'ml', 'base_price'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
