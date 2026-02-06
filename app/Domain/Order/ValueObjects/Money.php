<?php

namespace App\Domain\Order\ValueObjects;

class Money
{
    public function __construct(private int $amount) {}

    public function add(Money $other): Money
    {
        return new Money($this->amount + $other->amount);
    }

    public function value(): int
    {
        return $this->amount;
    }
}
