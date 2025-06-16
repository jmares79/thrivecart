<?php

namespace Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderCreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_order_with_products(): void
    {
        // Arrange: Create products in the database
        $product1 = Product::factory()->create(['name' => 'Product 1', 'price' => 100]);
        $product2 = Product::factory()->create(['name' => 'Product 2', 'price' => 200]);

        // Act: Send a POST request to create an order
        $response = $this->postJson('/api/orders', [
            'description' => 'Test order',
            'products' => [
                ['id' => $product1->id, 'amount' => 2],
                ['id' => $product2->id, 'amount' => 1],
            ],
        ]);

        // Assert: Check the response and database state
        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'order']);

        $this->assertDatabaseHas('orders', [
            'status' => 'pending',
            'description' => null,
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
