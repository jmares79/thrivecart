<?php

namespace App\Http\Interfaces;

use App\Models\Product;

interface OfferProcessingInterface
{
    public function calculate(Product $product): float;
}
