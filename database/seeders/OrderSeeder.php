<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'customer_id' => 3,
            'processed_by' => null,
            'status' => 'pending',
            'notes' => 'Waiting for staff to process.'
        ]);

        Order::create([
            'customer_id' => 3,
            'processed_by' => 1,
            'status' => 'processing',
            'notes' => 'Order is being prepared.'
        ]);

        Order::create([
            'customer_id' => 3,
            'processed_by' => null,
            'status' => 'pending',
            'notes' => 'Customer added items to the order.'
        ]);

        Order::create([
            'customer_id' => 3,
            'processed_by' => 2,
            'status' => 'completed',
            'notes' => 'Delivered to customer.'
        ]);

        Order::create([
            'customer_id' => 3  ,
            'processed_by' => null,
            'status' => 'cancelled',
            'notes' => 'Customer requested cancellation.'
        ]);
    }
}
