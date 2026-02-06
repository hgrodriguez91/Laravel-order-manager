<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Entities\Order;

interface OrderRepository
{
    public function save(Order $order): void;
}

