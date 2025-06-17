<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Request $request, Order $order)
    {
        return response()->json([
            'order' => $order->load('products'),
        ]);
    }

    public function store(OrderCreationRequest $request)
    {
        $order = Order::create([
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        foreach ($request->input('products', []) as $product) {
            $order->products()->attach($product['id'], ['amount' => $product['amount']]);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }
}
