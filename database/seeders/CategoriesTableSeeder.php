<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Import the model

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Women Shoes', 'description' => 'All kinds of  women shoes '],
            ['name' => 'Mens Wear', 'description' => 'All kinds of men Wear'],
            ['name' => 'Mens Shoes', 'description' => 'All kinds of men shoes'],
            ['name' => 'Women Wear', 'description' => 'All kinds of  women Wear'],
            ['name'=> 'Kids Shoes', 'description'=> 'ALl kinds of kids Shoes'],
            ['name'=> 'kids Wear', 'description'=> 'All kinds of Kids Wear'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
