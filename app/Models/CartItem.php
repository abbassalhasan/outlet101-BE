<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable =
    [
        "cart_id",
        "product_id",
        "amount"

    ];
        public function product()
        {
            return $this->belongsTo(Product::class);
        }
        public function cart_id()
        {
            return $this->belongsTo(Cart::class);
        }
}
