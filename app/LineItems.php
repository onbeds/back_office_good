<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineItems extends Model
{
    protected $table = 'lineitems';

    protected $casts = [
        'properties' => 'array',
        'tax_lines' => 'array',
        'origin_location' => 'array',
        'destination_location' => 'array'
    ];

    protected $guarded = [];

    public static function createLineItem($item, $order, $points, $shop)
    {
        LineItems::create([
            'variant_id' =>$item['variant_id'],
            'title' => $item['title'],
            'quantity' =>$item['quantity'],
            'price' => $item['price'],
            'grams' =>$item['grams'],
            'sku' => $item['sku'],
            'variant_title' =>$item['variant_title'],
            'vendor' => $item['vendor'],
            'fulfillment_service' =>$item['fulfillment_service'],
            'product_id' => $item['product_id'],
            'requires_shipping' =>$item['requires_shipping'],
            'taxable' => $item['taxable'],
            'gift_card' =>$item['gift_card'],
            'pre_tax_price' => $item['pre_tax_price'],
            'name' =>$item['name'],
            'variant_inventory_management' => $item['variant_inventory_management'],
            'properties' =>$item['properties'],
            'product_exists' => $item['product_exists'],
            'fulfillable_quantity' =>$item['fulfillable_quantity'],
            'total_discount' => $item['total_discount'],
            'fulfillment_status' =>$item['fulfillment_status'],
            'tax_lines' => $item['tax_lines'],
            'origin_location' =>(isset($item['origin_location'])) ? isset($item['origin_location']) : null,
            'destination_location' => (isset($item['destination_location'])) ? $item['destination_location'] : null,
            'order_name' => $order['name'],
            'order_id' => $order['id'],
            'date_order' =>$order['updated_at'],
            'points' => $points,
            'line_item_id' => $item['id'],
            'shop' => $shop
        ]);
    }
}
