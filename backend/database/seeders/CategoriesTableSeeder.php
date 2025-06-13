<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "Electronics",
                "slug" => "electronics",
                "description" => "Electronic devices and gadgets",
            ],
            [
                "name" => "Clothing",
                "slug" => "clothing",
                "description" => "Fashion and apparel",
            ],
            [
                "name" => "Books",
                "slug" => "books",
                "description" => "Books and literature",
            ],
            [
                "name" => "Home & Kitchen",
                "slug" => "home-kitchen",
                "description" => "Home appliances and kitchen accessories",
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
