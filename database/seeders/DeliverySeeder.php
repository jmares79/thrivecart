<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('deliveries')->insert([
            ['name' => 'Standard Delivery', 'cost' => 4.95, 'description' => 'Standard delivery', 'product_amount' => 50.00, 'condition' => '<='],
            ['name' => 'Custom Delivery', 'cost' => 2.95, 'description' => 'Custom Delivery', 'product_amount' => 90.00, 'condition' => '<='],
            ['name' => 'Free Delivery', 'cost' => 0.00, 'description' => 'Free delivery', 'product_amount' => 90.00, 'condition' => '>'],
        ]);
    }
}
