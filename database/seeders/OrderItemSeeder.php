<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;    // <-- Add this
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Get all order IDs and product IDs
        $orderIds = Order::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        // Check if there are any orders and products first
        if (empty($orderIds) || empty($productIds)) {
            $this->command->info('No orders or products found. Please seed orders and products first.');
            return;
        }

        // Generate 50 order items randomly
        for ($i = 0; $i < 20; $i++) {
            $productId = $productIds[array_rand($productIds)];
            $orderId = $orderIds[array_rand($orderIds)];
            
            // Assuming unit_price comes from product price or can be random
            // Here just a random price between 10 and 100 for demo
            $unitPrice = mt_rand(10, 100);

            // Random quantity between 1 and 5
            $quantity = mt_rand(1, 5);

            OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);
        }
    }
}
