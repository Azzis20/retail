<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;



class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Product::create([
            'product_name' => 'Tomato',
            'category' => 'vegetable',
            'price' => 2.50,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Potato',
            'category' => 'vegetable',
            'price' => 1.80,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Carrot',
            'category' => 'vegetable',
            'price' => 2.20,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Onion',
            'category' => 'vegetable',
            'price' => 1.90,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Cabbage',
            'category' => 'vegetable',
            'price' => 1.50,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Rice',
            'category' => 'grocery',
            'price' => 40.00,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Wheat Flour',
            'category' => 'grocery',
            'price' => 35.00,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Sugar',
            'category' => 'grocery',
            'price' => 50.00,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Salt',
            'category' => 'grocery',
            'price' => 20.00,
            'unit' => 'kg'
        ]);

        Product::create([
            'product_name' => 'Cooking Oil',
            'category' => 'grocery',
            'price' => 150.00,
            'unit' => 'liter'
        ]);


    }
}


// protected $fillable = [
//         'product_name',
//         'category',
//         'price',
//         'unit',
//     ];