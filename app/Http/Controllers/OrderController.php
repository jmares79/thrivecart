<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use App\Models\Order;

class OrderController extends Controller
{
    public function __invoke(OrderCreationRequest $request)
    {
        $order = Order::create([
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        foreach ($request->input('products', []) as $product) {
            $order->products()->attach($product['id'], ['amount' => $product['amount']]);
        }

        dd($order, $order->products);
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }
}
