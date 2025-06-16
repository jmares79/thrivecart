<?php

namespace App\Logic;

use App\Models\Order;

class BasketLogic
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function calculateTotal(Order $order): float
    {
        // Assuming the Order model has a relationship with Product
        $total = 0.0;

        foreach ($order->products as $product) {
            $total += $product->price * $product->pivot->amount; // Assuming pivot table has 'amount'
        }

        return $total;
    }

}
