<?php

namespace App\Logic\Strategies;

use App\Http\Interfaces\OfferProcessingInterface;
use App\Models\Product;

class OneRedWidgetSecondHalfPrice implements OfferProcessingInterface
{
    /**
     * Calculate the total price of the order, applying the "Buy One Red Widget Get Other For Free" offer.
     *
     * @param Product $product
     * @return float
     */
    public function calculate(Product $product): float
    {
        $price = $product->price;
        $productAmount = $product->pivot->amount;

        dump("Product: {$product->name}, Price: {$price}, Amount: {$productAmount}");
        $pairs = intdiv($productAmount, 2);
        $remainder = $productAmount % 2;

        return $pairs * ($price + $price / 2) + $remainder * $price;
    }
}
