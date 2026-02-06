<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repositories\OrderRepository;
use App\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $orderModel = OrderModel::create([
                'customer_id' => $order->customerId(),
                'total'       => $order->total()->value(),
                'confirmed'   => $order->isConfirmed(),
            ]);

            foreach ($order->items() as $item) {
                OrderItemModel::create([
                    'order_id' => $orderModel->id,
                    'price'   => $item->value(),
                ]);
            } 
        });
    }
}


