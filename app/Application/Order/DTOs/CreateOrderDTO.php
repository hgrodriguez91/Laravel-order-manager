<?php

namespace App\Application\Order\DTOs;

class CreateOrderDTO
{
    public function __construct(
        public int $customerId,
        public array $items
    ) {}
}
