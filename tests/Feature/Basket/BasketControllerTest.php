<?php

namespace Tests\Feature\Basket;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use Database\Seeders\DeliverySeeder;
use Database\Seeders\OfferSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BasketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $route = '/api/orders/{order}/total';

    public function setUp():void
    {
        parent::setUp();

        $this->seed(DeliverySeeder::class);
        $this->seed(OfferSeeder::class);
    }

    #[DataProvider('orderProvider')]
    public function test_can_process_order_total_with_fees_no_offers($order, $products, $expectedSubtotal, $expectedDeliveryFee): void
    {
        $order = Order::factory()->create(['description' => $order['description'], 'status' => $order['status']]);

        foreach ($products as $productData) {
            $amount = array_pop($productData);

            $product = Product::factory()->create($productData);
            $order->products()->attach($product->id, ['amount' => $amount]);
        }

        $response = $this->postJson(str_replace('{order}', $order->id, $this->route));

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'order', 'Order subtotal', 'Delivery fees', 'Order total']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processed',
        ]);

        $this->assertEquals($expectedSubtotal, $response->json('Order subtotal'));
        $this->assertEquals($expectedDeliveryFee, $response->json('Delivery fees'));
    }

    #[DataProvider('orderWithOffersProvider')]
    public function test_can_process_order_total_with_fees_and_offers($order, $products, $offer, $expectedSubtotal, $expectedDeliveryFee): void
    {
        $order = Order::factory()->create(['description' => $order['description'], 'status' => $order['status']]);

        if (! Offer::where('code', $offer['code'])->exists()) {
            Offer::create($offer);
        }

        foreach ($products as $productData) {
            $amount = array_pop($productData);

            $product = Product::factory()->create($productData);
            $order->products()->attach($product->id, ['amount' => $amount]);
        }

        $response = $this->postJson(str_replace('{order}', $order->id, $this->route))
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'order',
                'Order subtotal',
                'Delivery fees',
                'Order total'
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processed',
        ]);

        $this->assertEquals($expectedSubtotal, $response->json('Order subtotal'));
        $this->assertEquals($expectedDeliveryFee, $response->json('Delivery fees'));
    }

    public static function orderWithOffersProvider(): array
    {
        return [
            '2_prods_first_delivery_fee' => [
                'order' => ['description' => 'order_with_two_products_under_50_delivery', 'status' => 'pending'],
                'products' => [
                    ['name' => 'Red widget', 'code' => 'R01', 'price' => 10, 'amount' => 2],
                    ['name' => 'Blue widget', 'code' => 'B01', 'price' => 5, 'amount' => 1],
                ],
                'offer' => ['product_code' => 'R01', 'code' => 'ONE_RED_WIDGET_SECOND_HALF_PRICE'],
                'expectedSubtotal' => 20.0,
                'expectedDeliveryFee' => 4.95,
            ],
            '2_prods_second_delivery_fee' => [
                'order' => ['description' => 'order_with_two_products_under_90_delivery', 'status' => 'pending'],
                'products' => [
                    ['name' => 'Red widget', 'code' => 'R01', 'price' => 10, 'amount' => 5],
                    ['name' => 'Blue widget', 'code' => 'B01', 'price' => 50, 'amount' => 1],
                ],
                'offer' => ['product_code' => 'R01', 'code' => 'ONE_RED_WIDGET_SECOND_HALF_PRICE'],
                'expectedSubtotal' => 90.0,
                'expectedDeliveryFee' => 2.95,
            ],
        ];
    }

    public static function orderProvider(): array
    {
        return [
            'order_with_two_products_with_first_delivery_fee' => [
                'order' => ['description' => 'order_with_two_products_under_50_delivery', 'status' => 'pending'],
                'products' => [
                    ['name' => 'Red widget', 'code' => 'R01', 'price' => 10, 'amount' => 2],
                    ['name' => 'Blue widget', 'code' => 'B01', 'price' => 5, 'amount' => 1],
                ],
                'expectedSubtotal' => 25.0,
                'expectedDeliveryFee' => 4.95,
            ],
            'order_with_two_products_with_second_delivery_fee' => [
                'order' => ['description' => 'order_with_two_products_under_90_delivery', 'status' => 'pending'],
                'products' => [
                    ['name' => 'Red widget', 'code' => 'R01', 'price' => 10, 'amount' => 2],
                    ['name' => 'Blue widget', 'code' => 'B01', 'price' => 50, 'amount' => 1],
                ],
                'expectedSubtotal' => 70.0,
                'expectedDeliveryFee' => 2.95,
            ],
        ];
    }
}
