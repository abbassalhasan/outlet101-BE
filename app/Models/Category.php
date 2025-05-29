<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // If your table name is 'categories', you don't need to specify $table,
    // Laravel assumes plural form of the model name by default.

    // Fillable properties for mass assignment:
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
