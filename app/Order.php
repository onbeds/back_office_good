<?php

namespace App;

use App\Code;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Entities\Tercero;
use App\Entities\Network;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected static $url;
    protected static $client;
    protected $table = 'orders';

    protected $casts = [
        'billing_address' => 'array',
        'client_details' => 'array',
        'customer' => 'array',
        'discount_codes' => 'array',
        'fulfillments' => 'array',
        'line_items' => 'array',
        'note_attributes' => 'array',
        'payment_details' => 'array',
        'payment_gateway_names' => 'array',
        'shipping_address' => 'array',
        'shipping_lines' => 'array',
        'tax_lines' => 'array',
        'refunds' => 'array',
        'bitacora' => 'array'
    ];

    protected $guarded = [];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id', 'id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }

    public static function createOrder($order, $shop, $points, $tipo_orden, $id)
    {
        date_default_timezone_set('America/Bogota');
        return Order::create([
            'billing_address' => (isset($order['billing_address'])) ? $order['billing_address'] : null,
            'browser_ip' => $order['browser_ip'],
            'buyer_accepts_marketing' => $order['buyer_accepts_marketing'],
            'cancel_reason' => $order['cancel_reason'],
            'cancelled_at' => $order['cancelled_at'],
            'cart_token' => $order['cart_token'],
            'client_details' => (isset($order['client_details']) ? $order['client_details'] : null),
            'closed_at' => $order['closed_at'],
            'currency' => $order['currency'],
            'customer_id' => $order['id'],
            'discount_codes' => $order['discount_codes'],
            'email' => strtolower($order['email']),
            'financial_status' => $order['financial_status'],
            'fulfillments' => $order['fulfillments'],
            'fulfillment_status' => $order['fulfillment_status'],
            'tags' => $order['tags'],
            'gateway' => $order['gateway'],
            'landing_site' => $order['landing_site'],
            'landing_site_ref' => $order['landing_site_ref'],
            'line_items' => $order['line_items'],
            'location_id' => $order['location_id'],
            'name' => $order['name'],
            'network_id' => 1,
            'note' => $order['note'],
            'note_attributes' => $order['note_attributes'],
            'number' => $order['number'],
            'order_id' => (int)$order['id'],
            'order_number' => $order['order_number'],
            'payment_details' => null,
            'payment_gateway_names' => $order['payment_gateway_names'],
            'phone' => $order['phone'],
            'processed_at' => Carbon::parse($order['processed_at']),
            'processing_method' => $order['processing_method'],
            'referring_site' => $order['referring_site'],
            'refunds' => $order['refunds'],
            'shipping_address' => (!empty($order['shipping_address'])) ? $order['shipping_address'] : null,
            'shipping_lines' => $order['shipping_lines'],
            'source_name' => $order['source_name'],
            'subtotal_price' => $order['subtotal_price'],
            'tax_lines' => $order['tax_lines'],
            'taxes_included' => $order['taxes_included'],
            'token' => $order['token'],
            'total_discounts' => $order['total_discounts'],
            'total_line_items_price' => $order['total_line_items_price'],
            'total_price' => $order['total_price'],
            'total_tax' => $order['total_tax'],
            'total_weight' => $order['total_weight'],
            'user_id' => $order['user_id'],
            'order_status_url' => $order['order_status_url'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'test' => $order['test'],
            'confirmed' => $order['confirmed'],
            'total_price_usd' => $order['total_price_usd'],
            'checkout_token' => $order['checkout_token'],
            'reference' => $order['reference'],
            'source_identifier' => $order['source_identifier'],
            'source_url' => $order['source_url'],
            'device_id' => $order['device_id'],
            'checkout_id' => $order['checkout_id'],
            'origin' => 'webhooks',
            'tipo_orden' => $tipo_orden,
            'points' => $points,
            'shop' => $shop,
            'tercero_id' => $id
        ]);
    }

    public static function duplicateOrder($order)
    {
        date_default_timezone_set('America/Bogota');
        return Order::create([
            'billing_address' => $order['billing_address'],
            'browser_ip' => $order['browser_ip'],
            'buyer_accepts_marketing' => $order['buyer_accepts_marketing'],
            'cancel_reason' => $order['cancel_reason'],
            'cancelled_at' => $order['cancelled_at'],
            'cart_token' => $order['cart_token'],
            'client_details' => $order['client_details'],
            'closed_at' => $order['closed_at'],
            'currency' => $order['currency'],
            'customer_id' => $order['id'],
            'discount_codes' => $order['discount_codes'],
            'email' => strtolower($order['email']),
            'financial_status' => $order['financial_status'],
            'fulfillments' => $order['fulfillments'],
            'fulfillment_status' => $order['fulfillment_status'],
            'tags' => $order['tags'],
            'gateway' => $order['gateway'],
            'landing_site' => $order['landing_site'],
            'landing_site_ref' => $order['landing_site_ref'],
            'line_items' => $order['line_items'],
            'location_id' => $order['location_id'],
            'name' => $order['name'],
            'network_id' => 1,
            'note' => $order['note'],
            'note_attributes' => $order['note_attributes'],
            'number' => $order['number'],
            'order_id' => $order['order_id'],
            'order_number' => $order['order_number'],
            'payment_details' => null,
            'payment_gateway_names' => $order['payment_gateway_names'],
            'phone' => $order['phone'],
            'processed_at' => Carbon::parse($order['processed_at']),
            'processing_method' => $order['processing_method'],
            'referring_site' => $order['referring_site'],
            'refunds' => $order['refunds'],
            'shipping_address' =>$order['shipping_address'],
            'shipping_lines' => $order['shipping_lines'],
            'source_name' => $order['source_name'],
            'subtotal_price' => $order['subtotal_price'],
            'tax_lines' => $order['tax_lines'],
            'taxes_included' => $order['taxes_included'],
            'token' => $order['token'],
            'total_discounts' => $order['total_discounts'],
            'total_line_items_price' => $order['total_line_items_price'],
            'total_price' => $order['total_price'],
            'total_tax' => $order['total_tax'],
            'total_weight' => $order['total_weight'],
            'user_id' => $order['user_id'],
            'order_status_url' => $order['order_status_url'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'test' => $order['test'],
            'confirmed' => $order['confirmed'],
            'total_price_usd' => $order['total_price_usd'],
            'checkout_token' => $order['checkout_token'],
            'reference' => $order['reference'],
            'source_identifier' => $order['source_identifier'],
            'source_url' => $order['source_url'],
            'device_id' => $order['device_id'],
            'checkout_id' => $order['checkout_id'],
            'origin' => $order['origin'],
            'tipo_orden' => $order['tipo_orden'],
            'points' => ((int)$order['points']) * (-1),
            'shop' => 'duplicado',
            'tercero_id' => $order['tercero_id']
        ]);
    }

    public function codes()
    {
        return $this->hasMany(Code::class, 'order_id');
    }
}
