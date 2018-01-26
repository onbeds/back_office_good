<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Order;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model {

    protected static $url;
    protected static $client;
    protected $table = 'transactions';
    protected $casts = [
        'receipt' => 'array',
        'payment_details' => 'array',
    ];
    protected $guarded = [];

    public function tercero() {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public static function saveTransaction($transaction, $shop) {
        $model = self::where('transaction_id', $transaction['id'])->first();
        if ($model == NULL) {
            $model = new Transactions();
            $model->transaction_id = $transaction['id'];
            $model->order_id = $transaction['order_id'];
            $model->created_at = Carbon::parse($transaction['created_at']);
            $model->shop = $shop;
        }
        $model->amount = $transaction['amount'];
        $model->kind = $transaction['kind'];
        $model->gateway = $transaction['gateway'];
        $model->status = $transaction['status'];
        $model->message = $transaction['message'];
        $model->test = $transaction['test'];
        $model->authorization = $transaction['authorization'];
        $model->currency = $transaction['currency'];
        $model->location_id = $transaction['location_id'];
        $model->user_id = $transaction['user_id'];
        $model->parent_id = $transaction['parent_id'];
        $model->device_id = $transaction['device_id'];
        $model->receipt = $transaction['receipt'];
        $model->error_code = $transaction['error_code'];
        $model->source_name = $transaction['source_name'];
        $model->payment_details = isset($transaction['payment_details']) ? $transaction['payment_details'] : NULL;

        return $model->save();
    }

}
