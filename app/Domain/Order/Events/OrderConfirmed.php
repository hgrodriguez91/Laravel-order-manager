<?php

namespace App\Domain\Order\Events;

class OrderConfirmed
{
    public function __construct(public int $orderId) {}
}
