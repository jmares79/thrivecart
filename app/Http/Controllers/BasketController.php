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
        // Check if the order exists and is pending
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order not found or already processed.',
            ], 404);
        }

        $total = $this->basketLogic->calculateTotal($order);
        // Process the order (e.g., change status, update stock, etc.)
        $order->status = 'processed';
        $order->save();


        return response()->json([
            'message' => 'Order processed successfully',
            'order' => $total,
        ]);
    }
}
