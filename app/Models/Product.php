<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "name",
        "price",
        "amount",
        "slug",
        "description",
        "imgSrc",
        "category_id",
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}


