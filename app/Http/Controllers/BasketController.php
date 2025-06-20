<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\OfferNotFoundException;
use App\Logic\BasketLogic;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function __construct(protected readonly BasketLogic $basketLogic) {}

    /**
     * Process the order loaded in the database (basket)
     */
    public function __invoke(Request $request, Order $order): JsonResponse
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order already processed.',
            ], 403);
        }

        try {
            $total = $this->basketLogic->calculateTotal($order);
            $fee = $this->basketLogic->calculateDelivery($total);

            $order->status = 'processed';
            $order->save();

            return response()->json([
                'message' => 'Order processed successfully',
                'order' => $order,
                'Order subtotal' => $total,
                'Delivery fees' => $fee,
                'Order total' => $total + $fee,
            ]);
        } catch (OfferNotFoundException $e) {
            return response()->json([
                'message' => 'Offer not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
