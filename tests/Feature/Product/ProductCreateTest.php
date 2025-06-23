<?php

namespace Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    protected string $route = '/api/products';

    public function test_it_returns_unprocessable_on_invalid_payload(): void
    {
        $this->postJson($this->route, [
            'code' => 5,
            'price' => 'invalid product price',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'code']);
    }

    public function test_it_creates_an_order_with_products(): void
    {
        $response = $this->postJson($this->route, [
            'name' =>'Product 1',
            'code' => 'P01',
            'price' => 10
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'product']);

        $this->assertDatabaseCount(Product::class, 1);
        $this->assertDatabaseHas('products', [
            'name' =>'Product 1',
            'code' => 'P01',
            'price' => 10
        ]);


    }
}
