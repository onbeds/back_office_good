<?php

namespace App;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'codes';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
