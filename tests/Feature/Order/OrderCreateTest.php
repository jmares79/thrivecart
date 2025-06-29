<?php

namespace Tests\Feature\Order;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController::store
 */
class OrderCreateTest extends TestCase
{
    use RefreshDatabase;

    protected string $route = '/api/orders';

    #[Test]
    public function it_creates_an_order_with_products(): void
    {
        $product1 = Product::factory()->create(['name' => 'Red widget', 'price' => 100]);
        $product2 = Product::factory()->create(['name' => 'Blue widget', 'price' => 90]);

        $response = $this->postJson($this->route, [
            'description' => 'Test order',
            'products' => [
                ['id' => $product1->id, 'amount' => 2],
                ['id' => $product2->id, 'amount' => 1],
            ],
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'order']);

        $this->assertDatabaseHas('orders', [
            'status' => 'pending',
            'description' => 'Test order',
        ]);

        $this->assertDatabaseHas('order_product', [
            'product_id' => $product1->id,
            'amount' => 2,
        ]);

        $this->assertDatabaseHas('order_product', [
            'product_id' => $product2->id,
            'amount' => 1,
        ]);
    }
}
