<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTOs\CreateOrderDTO;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repositories\OrderRepository;
use App\Domain\Order\ValueObjects\Money;

class CreateOrderUseCase
{
    public function __construct(private OrderRepository $orders) {}

    public function execute(CreateOrderDTO $dto): Order
    {
        $order = new Order(rand(1, 9999), $dto->customerId);

        foreach ($dto->items as $price) {
            $order->addItem(new Money($price));
        }

        $order->confirm();

        $this->orders->save($order);

        return $order;
    }
}