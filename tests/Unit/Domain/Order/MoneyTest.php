<?php

namespace Tests\Unit\Domain\Order;

use App\Domain\Order\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /** @test */
    public function it_stores_a_value()
    {
        $money = new Money(100);

        $this->assertEquals(100, $money->value());
    }

    /** @test */
    public function it_adds_two_money_objects()
    {
        $a = new Money(100);
        $b = new Money(50);

        $result = $a->add($b);

        $this->assertEquals(150, $result->value());
    }
}