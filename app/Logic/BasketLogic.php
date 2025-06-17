<?php

namespace App\Logic;

use App\Models\Order;

class BasketLogic
{
    /**
     * Calculate the total price of the order, applying offers and deliveries if any.
     *
     * @param Order $order
     * @return float
     */
    public function calculateTotal(Order $order): float
    {
        $total = 0.0;
        dump('ORDER', $order->id);

        foreach ($order->products as $product) {

            dd('PRODUCT', $product->id, $product->pivot->amount);
            $total += $product->price * $product->pivot->amount;
        }

        return $total;
    }

}
