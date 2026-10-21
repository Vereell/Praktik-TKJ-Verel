<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Variant extends Model
{
    protected $fillable = ['name', 'description', 'emoji', 'img'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
