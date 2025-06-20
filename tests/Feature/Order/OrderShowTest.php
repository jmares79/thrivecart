<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController::show
 */
class OrderShowTest extends TestCase
{
    use RefreshDatabase;

    protected string $route = '/api/orders/{order}';

    #[Test]
    public function can_show_an_order_with_products(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $order->products()->attach(Product::factory()->create(['name' => 'Test Product', 'price' => 100]), ['amount' => 1]);

        $response = $this->getJson(str_replace('{order}', $order->id, $this->route));

        $response->assertStatus(200)
            ->assertJsonStructure(['order' => ['id', 'description', 'status', 'products']])
            ->assertJsonFragment(['description' => $order->description, 'status' => 'pending']);
    }
}
