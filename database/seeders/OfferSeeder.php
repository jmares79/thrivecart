<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('offers')->insert([
            ['name' => 'Buy 1 red widget and get the second half price', 'code' => 'ONE_RED_WIDGET_SECOND_HALF_PRICE', 'product_code' => 'R01'],
        ]);
    }
}
