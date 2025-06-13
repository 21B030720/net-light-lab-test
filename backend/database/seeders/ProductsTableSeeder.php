<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electronics
            [
                "category_id" => 1,
                "name" => "Smartphone X",
                "slug" => "smartphone-x",
                "description" => "Latest smartphone with advanced features",
                "price" => 999.99,
                "stock" => 50,
                "image_url" => "products/smartphone.jpg",
                "is_active" => true,
            ],
            [
                "category_id" => 1,
                "name" => "Laptop Pro",
                "slug" => "laptop-pro",
                "description" => "High-performance laptop for professionals",
                "price" => 1299.99,
                "stock" => 30,
                "image_url" => "products/laptop.jpg",
                "is_active" => true,
            ],

            // Clothing
            [
                "category_id" => 2,
                "name" => "Cotton T-Shirt",
                "slug" => "cotton-t-shirt",
                "description" => "Comfortable cotton t-shirt for everyday wear",
                "price" => 24.99,
                "stock" => 100,
                "image_url" => "products/tshirt.jpg",
                "is_active" => true,
            ],
            [
                "category_id" => 2,
                "name" => "Denim Jeans",
                "slug" => "denim-jeans",
                "description" => "Classic denim jeans with perfect fit",
                "price" => 49.99,
                "stock" => 75,
                "image_url" => "products/jeans.jpg",
                "is_active" => true,
            ],

            // Books
            [
                "category_id" => 3,
                "name" => "Programming Guide",
                "slug" => "programming-guide",
                "description" => "Comprehensive guide to modern programming",
                "price" => 34.99,
                "stock" => 60,
                "image_url" => "products/programming-book.jpg",
                "is_active" => true,
            ],
            [
                "category_id" => 3,
                "name" => "Science Fiction Novel",
                "slug" => "science-fiction-novel",
                "description" => "Bestselling science fiction novel",
                "price" => 19.99,
                "stock" => 85,
                "image_url" => "products/sci-fi-book.jpg",
                "is_active" => true,
            ],

            // Home & Kitchen
            [
                "category_id" => 4,
                "name" => "Coffee Maker",
                "slug" => "coffee-maker",
                "description" => "Premium coffee maker for home use",
                "price" => 129.99,
                "stock" => 40,
                "image_url" => "products/coffee-maker.jpg",
                "is_active" => true,
            ],
            [
                "category_id" => 4,
                "name" => "Kitchen Knife Set",
                "slug" => "kitchen-knife-set",
                "description" =>
                    "Professional kitchen knife set with wood block",
                "price" => 79.99,
                "stock" => 55,
                "image_url" => "products/knife-set.jpg",
                "is_active" => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
