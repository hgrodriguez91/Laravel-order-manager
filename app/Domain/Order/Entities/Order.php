<?php

namespace App\Domain\Order\Entities;

use App\Domain\Order\ValueObjects\Money;
use App\Domain\Order\Traits\CalculatesTotals;
use App\Domain\Order\Events\OrderConfirmed;
use DomainException;

class Order
{
    use CalculatesTotals;

    private array $items = [];
    private bool $confirmed = false;

    public function __construct(
        private int $id,
        private int $customerId
    ) {}

    public function addItem(Money $price): void
    {
        $this->items[] = $price;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function customerId(): int
    {
        return $this->customerId;
    }
    
    public function confirm(): void
    {
        if (empty($this->items)) {
            throw new DomainException("Order cannot be confirmed without items");
        }

        $this->confirmed = true;
    }

    public function total(): Money
    {
        return $this->calculateTotal($this->items);
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function items(): array
    {
        return $this->items;
    }
}
