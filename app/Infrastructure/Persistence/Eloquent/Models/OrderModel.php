<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $fillable = ['customer_id', 'total', 'confirmed'];

    public function items()
{
    return $this->hasMany(OrderItemModel::class, 'order_id');
}
}
