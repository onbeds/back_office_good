<?php

namespace App;

use Carbon\Carbon;
use App\Entities\Network;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $dates = ['created_at'];

    protected $casts = [
        'addresses' => 'array',
        'default_address' => 'array',
        'metafield' => 'array'
    ];

    protected $guarded = [];

    public function networks()
    {
        return $this->belongsToMany(Network::class, 'terceros_networks', 'customer_id', 'network_id')->withPivot('network_id', 'padre_id', 'state')->withTimestamps();
    }

    public static function createCustomer($customer)
    {
        return Customer::create([
            'accepts_marketing' => $customer['accepts_marketing'],
            'addresses' => $customer['addresses'],
            'created_at' => Carbon::parse($customer['created_at']),
            'default_address' => (isset($customer['default_address'])) ? $customer['default_address'] : null,
            'email' => strtolower($customer['email']),
            'phone' => $customer['phone'],
            'first_name' => $customer['first_name'],
            'customer_id' => $customer['id'],
            'metafield' => null,
            'multipass_identifier' => $customer['multipass_identifier'],
            'last_name' => strtolower($customer['last_name']),
            'last_order_id' => $customer['last_order_id'],
            'last_order_name' => $customer['last_order_name'],
            'network_id' => 1,
            'note' => $customer['note'],
            'orders_count' => $customer['orders_count'],
            'state' => $customer['state'],
            'tags' => $customer['tags'],
            'tax_exempt' => $customer['tax_exempt'],
            'total_spent' => $customer['total_spent'],
            'updated_at' => Carbon::parse($customer['updated_at']),
            'verified_email' => $customer['verified_email'],
        ]);
    }

}
