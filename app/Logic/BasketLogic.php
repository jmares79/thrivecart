<?php

namespace App\Logic;

use App\Exceptions\OfferNotFoundException;
use App\Logic\Factories\OfferProcessingFactory;
use App\Models\Offer;
use App\Models\Order;

class BasketLogic
{
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
//                dd('SUBTOTAL', $subTotal, 'OFFER', $offer->product_code);
            } else {
                $subTotal = $product->price * $product->pivot->amount;
            }

            $total += $subTotal;
        }

        return $total;
    }

}
