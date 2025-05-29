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
            ['name' => 'Women Shoes',"slug"=>"Women_shoes", 'description' => 'All kinds of  women shoes '],
            ['name' => 'Mens Wear',"slug"=>"Mens_Wear", 'description' => 'All kinds of men Wear'],
            ['name' => 'Mens Shoes',"slug"=>"Mens_shoes", 'description' => 'All kinds of men shoes'],
            ['name' => 'Women Wear',"slug"=>"Women_Wear", 'description' => 'All kinds of  women Wear'],
            ['name'=> 'Kids Shoes',"slug"=>"Kids_shoes", 'description'=> 'ALl kinds of kids Shoes'],
            ['name'=> 'kids Wear',"slug"=>"Kids_Wear", 'description'=> 'All kinds of Kids Wear'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
