<?php

declare(strict_types=1);

namespace App\Logic;

use App\Exceptions\OfferNotFoundException;
use App\Logic\Factories\OfferProcessingFactory;
use App\Models\Delivery;
use App\Models\Offer;
use App\Models\Order;

class BasketLogic
{
    const FREE_DELIVERY = 0.0;

    /**
     * Calculate the total price of the order, applying offers and deliveries if any.
     *
     * @param Order $order
     * @return float
     * @throws OfferNotFoundException
     */
    public function calculateTotal(Order $order): float
    {
        $total = 0.0;

        // Get products in the order and all related offers
        $orderProducts = $order->products;
        $offers = Offer::whereIn('product_code', $orderProducts->pluck('code'))->get();

        // For each product in the order, calculate price and apply offers if any
        foreach ($orderProducts as $product) {
            // Check if the product has an offer
            $offer = $offers->where('product_code', $product->code)->first();

            if  ($offer) {
                if (! $offerStrategy = OfferProcessingFactory::make($offer->code)) {
                    throw new OfferNotFoundException("Offer not found for product: {$product->code}");
                }

                $subTotal = $offerStrategy->calculate($product);
            } else {
                $subTotal = $product->price * $product->pivot->amount;
            }

            $total += $subTotal;
        }

        return $total;
    }

    public function calculateDelivery(float $total): float
    {
        $fee = self::FREE_DELIVERY;

        // This can be a delivery order calculator, for now and for simplicity we implement here in the logic
        $deliveriesUnder = Delivery::where('condition', '<=')->orderByDesc('product_amount')->get();

        foreach ($deliveriesUnder as $delivery) {
            if ($total <= $delivery->product_amount) {
                $fee = $delivery->cost;
            }
        }

        return $fee;
    }
}
