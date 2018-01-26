<?php

namespace App;


use App\Variant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $casts = [
        'image' => 'array',
        'images' => 'array',
        'variants' => 'array',
        'options' => 'array',
    ];
    protected $guarded = [];

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id');
    }

    public static function createProduct($product, $tipo_producto, $shop)
    {

        Product::create([
            'body_html' => $product['body_html'],
            'created_at' => Carbon::parse($product['created_at']),
            'handle' => $product['handle'],
            'id' => $product['id'],
            'image' => $product['image'],
            'images' => $product['images'],
            'options' => $product['options'],
            'product_type' => $product['product_type'],
            'published_at' => Carbon::parse($product['published_at']),
            'published_scope' => $product['published_scope'],
            'tags' => $product['tags'],
            'template_suffix' => ($product['template_suffix'] !== null ) ? $product['template_suffix'] : null,
            'title' => $product['title'],
            'metafields_global_title_tag' => (isset($product['metafields_global_title_tag'])) ? $product['metafields_global_title_tag'] : null,
            'metafields_global_description_tag' => (isset($product['metafields_global_description_tag'])) ? $product['metafields_global_description_tag'] : null,
            'updated_at' => Carbon::parse($product['updated_at']),
            'variants' => $product['variants'],
            'vendor' => $product['vendor'],
            'tipo_producto' => $tipo_producto,
            'shop' => $shop
        ]);
    }
}
