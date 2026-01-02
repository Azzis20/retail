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
        // VEGETABLES
Product::create(['product_name'=>'Tomato','category'=>'vegetable','price'=>120,'unit'=>'kg','available_stock'=>50,'min_threshold'=>10]);
Product::create(['product_name'=>'Potato','category'=>'vegetable','price'=>100,'unit'=>'kg','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Carrot','category'=>'vegetable','price'=>140,'unit'=>'kg','available_stock'=>35,'min_threshold'=>10]);
Product::create(['product_name'=>'Onion (Red)','category'=>'vegetable','price'=>160,'unit'=>'kg','available_stock'=>30,'min_threshold'=>10]);
Product::create(['product_name'=>'Garlic','category'=>'vegetable','price'=>260,'unit'=>'kg','available_stock'=>25,'min_threshold'=>8]);
Product::create(['product_name'=>'Cabbage','category'=>'vegetable','price'=>70,'unit'=>'kg','available_stock'=>30,'min_threshold'=>8]);
Product::create(['product_name'=>'Eggplant','category'=>'vegetable','price'=>110,'unit'=>'kg','available_stock'=>25,'min_threshold'=>8]);
Product::create(['product_name'=>'Okra','category'=>'vegetable','price'=>90,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Pechay','category'=>'vegetable','price'=>30,'unit'=>'bundle','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Kangkong','category'=>'vegetable','price'=>25,'unit'=>'bundle','available_stock'=>40,'min_threshold'=>10]);

Product::create(['product_name'=>'Ampalaya','category'=>'vegetable','price'=>120,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Sayote','category'=>'vegetable','price'=>60,'unit'=>'kg','available_stock'=>25,'min_threshold'=>8]);
Product::create(['product_name'=>'Upo','category'=>'vegetable','price'=>70,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Kalabasa','category'=>'vegetable','price'=>50,'unit'=>'kg','available_stock'=>30,'min_threshold'=>8]);
Product::create(['product_name'=>'Sitaw','category'=>'vegetable','price'=>100,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Patola','category'=>'vegetable','price'=>80,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Pipino','category'=>'vegetable','price'=>60,'unit'=>'kg','available_stock'=>25,'min_threshold'=>8]);
Product::create(['product_name'=>'Bell Pepper','category'=>'vegetable','price'=>220,'unit'=>'kg','available_stock'=>15,'min_threshold'=>5]);
Product::create(['product_name'=>'Lettuce','category'=>'vegetable','price'=>90,'unit'=>'bundle','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Mustasa','category'=>'vegetable','price'=>30,'unit'=>'bundle','available_stock'=>35,'min_threshold'=>10]);

Product::create(['product_name'=>'Malunggay','category'=>'vegetable','price'=>20,'unit'=>'bundle','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Luya','category'=>'vegetable','price'=>180,'unit'=>'kg','available_stock'=>15,'min_threshold'=>5]);
Product::create(['product_name'=>'Tanglad','category'=>'vegetable','price'=>25,'unit'=>'bundle','available_stock'=>30,'min_threshold'=>10]);
Product::create(['product_name'=>'Singkamas','category'=>'vegetable','price'=>60,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Gabi','category'=>'vegetable','price'=>85,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Kamote','category'=>'vegetable','price'=>75,'unit'=>'kg','available_stock'=>30,'min_threshold'=>8]);
Product::create(['product_name'=>'Radish (Labanos)','category'=>'vegetable','price'=>70,'unit'=>'kg','available_stock'=>20,'min_threshold'=>8]);
Product::create(['product_name'=>'Spring Onion','category'=>'vegetable','price'=>25,'unit'=>'bundle','available_stock'=>35,'min_threshold'=>10]);
Product::create(['product_name'=>'Chili Pepper','category'=>'vegetable','price'=>280,'unit'=>'kg','available_stock'=>10,'min_threshold'=>5]);

// GROCERY
Product::create(['product_name'=>'Rice (Regular)','category'=>'grocery','price'=>52,'unit'=>'kg','available_stock'=>100,'min_threshold'=>20]);
Product::create(['product_name'=>'Rice (Premium)','category'=>'grocery','price'=>65,'unit'=>'kg','available_stock'=>80,'min_threshold'=>20]);
Product::create(['product_name'=>'Brown Rice','category'=>'grocery','price'=>70,'unit'=>'kg','available_stock'=>60,'min_threshold'=>15]);
Product::create(['product_name'=>'Sugar (White)','category'=>'grocery','price'=>85,'unit'=>'kg','available_stock'=>50,'min_threshold'=>15]);
Product::create(['product_name'=>'Sugar (Brown)','category'=>'grocery','price'=>90,'unit'=>'kg','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Salt','category'=>'grocery','price'=>25,'unit'=>'kg','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Cooking Oil (Sachet)','category'=>'grocery','price'=>75,'unit'=>'pack','available_stock'=>50,'min_threshold'=>10]);
Product::create(['product_name'=>'Soy Sauce','category'=>'grocery','price'=>30,'unit'=>'pack','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Vinegar','category'=>'grocery','price'=>28,'unit'=>'pack','available_stock'=>40,'min_threshold'=>10]);
Product::create(['product_name'=>'Fish Sauce','category'=>'grocery','price'=>35,'unit'=>'pack','available_stock'=>35,'min_threshold'=>10]);

Product::create(['product_name'=>'Instant Noodles','category'=>'grocery','price'=>15,'unit'=>'pack','available_stock'=>80,'min_threshold'=>20]);
Product::create(['product_name'=>'Canned Sardines','category'=>'grocery','price'=>25,'unit'=>'piece','available_stock'=>60,'min_threshold'=>15]);
Product::create(['product_name'=>'Canned Corned Beef','category'=>'grocery','price'=>35,'unit'=>'piece','available_stock'=>50,'min_threshold'=>15]);
Product::create(['product_name'=>'Canned Tuna','category'=>'grocery','price'=>38,'unit'=>'piece','available_stock'=>45,'min_threshold'=>15]);
Product::create(['product_name'=>'Eggs','category'=>'grocery','price'=>9,'unit'=>'piece','available_stock'=>120,'min_threshold'=>30]);
Product::create(['product_name'=>'Coffee (3-in-1)','category'=>'grocery','price'=>12,'unit'=>'pack','available_stock'=>100,'min_threshold'=>25]);
Product::create(['product_name'=>'Powdered Milk','category'=>'grocery','price'=>15,'unit'=>'pack','available_stock'=>90,'min_threshold'=>25]);
Product::create(['product_name'=>'Bread Loaf','category'=>'grocery','price'=>65,'unit'=>'piece','available_stock'=>30,'min_threshold'=>10]);
Product::create(['product_name'=>'Laundry Detergent','category'=>'grocery','price'=>20,'unit'=>'pack','available_stock'=>70,'min_threshold'=>20]);
Product::create(['product_name'=>'Dishwashing Liquid','category'=>'grocery','price'=>25,'unit'=>'pack','available_stock'=>60,'min_threshold'=>15]);


    }
}


