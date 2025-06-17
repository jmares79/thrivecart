<?php

namespace App\Http\Controllers;

use App\Logic\BasketLogic;
use App\Models\Order;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function __construct(protected readonly BasketLogic $basketLogic) {}

    /**
     * Process the order loaded in the database (basket)
     */
    public function __invoke(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order already processed.',
            ], 403);
        }

        $total = $this->basketLogic->calculateTotal($order);

//        $order->status = 'processed';
        $order->save();

        return response()->json([
            'message' => 'Order processed successfully',
            'order' => $order,
            'total' => $total,
        ]);
    }
}
