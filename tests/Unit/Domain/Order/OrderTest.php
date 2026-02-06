<?php

namespace Tests\Unit\Domain\Order;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\ValueObjects\Money;
use DomainException;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /** @test */
    public function it_starts_with_no_items_and_not_confirmed()
    {
        $order = new Order(1, 10);

        $this->assertFalse($order->isConfirmed());
        $this->assertCount(0, $order->items());
    }

    /** @test */
    public function it_can_add_items()
    {
        $order = new Order(1, 10);

        $order->addItem(new Money(100));
        $order->addItem(new Money(50));

        $this->assertCount(2, $order->items());
    }

    /** @test */
    public function it_calculates_total_from_items()
    {
        $order = new Order(1, 10);

        $order->addItem(new Money(100));
        $order->addItem(new Money(50));

        $this->assertEquals(150, $order->total()->value());
    }

    /** @test */
    public function it_cannot_confirm_without_items()
    {
        $this->expectException(DomainException::class);

        $order = new Order(1, 10);
        $order->confirm();
    }

    /** @test */
    public function it_can_be_confirmed_if_it_has_items()
    {
        $order = new Order(1, 10);
        $order->addItem(new Money(100));

        $order->confirm();

        $this->assertTrue($order->isConfirmed());
    }
}