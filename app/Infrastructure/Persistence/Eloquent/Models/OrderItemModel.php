<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
      protected $table = 'order_items';
    protected $fillable = ['order_id', 'price'];

    public function order()
    {
        return $this->belongsTo(OrderModel::class);
    }
}
