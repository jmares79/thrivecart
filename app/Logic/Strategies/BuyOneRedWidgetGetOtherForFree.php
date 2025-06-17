<?php

namespace App\Logic\Strategies;

class BuyOneRedWidgetGetOtherForFree
{
    /**
     * Calculate the total price of the order, applying the "Buy One Red Widget Get Other For Free" offer.
     *
     * @param array $products
     * @return float
     */
    public function calculateTotal(array $products): float
    {
        $total = 0.0;
        $redWidgetCount = 0;

        foreach ($products as $product) {
            if ($product['type'] === 'red_widget') {
                $redWidgetCount += $product['amount'];
                $total += $product['price'] * $product['amount'];
            } else {
                $total += $product['price'] * $product['amount'];
            }
        }

        // Apply the offer: for every two red widgets, one is free
        if ($redWidgetCount > 1) {
            $freeRedWidgets = intdiv($redWidgetCount, 2);
            $total -= $freeRedWidgets * $products[0]['price']; // Assuming the first product is a red widget
        }

        return $total;
    }
}
