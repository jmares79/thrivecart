<?php

namespace App\Logic\Factories;

use App\Http\Interfaces\OfferProcessingInterface;
use App\Logic\Strategies\OneRedWidgetSecondHalfPrice;
use App\OfferTypes;

class OfferProcessingFactory
{
    public static function make(string $type): ?OfferProcessingInterface
    {
        return match ($type) {
            OfferTypes::ONE_RED_WIDGET_SECOND_HALF_PRICE => new OneRedWidgetSecondHalfPrice(),
            default => null
        };
    }
}
