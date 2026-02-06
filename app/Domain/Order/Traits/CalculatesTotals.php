<?php

namespace App\Domain\Order\Traits;

use App\Domain\Order\ValueObjects\Money;

trait CalculatesTotals
{
    public function calculateTotal(array $items): Money
    {
        return array_reduce(
            $items,
            fn($total, $price) => $total->add($price),
            new Money(0)
        );
    }
}