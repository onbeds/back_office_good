<?php

namespace App;


use App\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table = 'variants';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function createVariant($variant, $shop)
    {
        $va =  Variant::find($variant['id']);

        if (count($va) == 0) {
            $v = new Variant();
            $v->id = $variant['id'];
            $v->product_id = $variant['product_id'];
            $v->title = $variant['title'];
            $v->price = $variant['price'];
            $v->sku = $variant['sku'];
            $v->position = $variant['position'];
            $v->grams = $variant['grams'];
            $v->inventory_policy = $variant['inventory_policy'];
            $v->compare_at_price = $variant['compare_at_price'];
            $v->fulfillment_service = $variant['fulfillment_service'];
            $v->inventory_management = $variant['inventory_management'];
            $v->option1 = $variant['option1'];
            $v->option2 = $variant['option2'];
            $v->option3 = $variant['option3'];
            $v->taxable = $variant['taxable'];
            $v->barcode = $variant['barcode'];
            $v->image_id = $variant['image_id'];
            $v->inventory_quantity = $variant['inventory_quantity'];
            $v->weight = $variant['weight'];
            $v->weight_unit = $variant['weight_unit'];
            $v->old_inventory_quantity = $variant['old_inventory_quantity'];
            $v->requires_shipping = $variant['requires_shipping'];
            $v->created_at = Carbon::parse($variant['created_at']);
            $v->updated_at = Carbon::parse($variant['updated_at']);
            $v->shop = $shop;
            $v->save();

        } else {

            $va->title = $variant['title'];
            $va->price = $variant['price'];
            $va->sku = $variant['sku'];
            $va->position = $variant['position'];
            $va->grams = $variant['grams'];
            $va->inventory_policy = $variant['inventory_policy'];
            $va->compare_at_price = $variant['compare_at_price'];
            $va->fulfillment_service = $variant['fulfillment_service'];
            $va->inventory_management = $variant['inventory_management'];
            $va->option1 = $variant['option1'];
            $va->option2 = $variant['option2'];
            $va->option3 = $variant['option3'];
            $va->taxable = $variant['taxable'];
            $va->barcode = $variant['barcode'];
            $va->image_id = $variant['image_id'];
            $va->inventory_quantity = $variant['inventory_quantity'];
            $va->weight = $variant['weight'];
            $va->weight_unit = $variant['weight_unit'];
            $va->old_inventory_quantity = $variant['old_inventory_quantity'];
            $va->requires_shipping = $variant['requires_shipping'];
            $va->created_at = Carbon::parse($variant['created_at']);
            $va->updated_at = Carbon::parse($variant['updated_at']);
            $va->save();
        }
    }

    public static function updateVariant($variant, $shop)
    {
        $v =  Variant::find($variant['id']);
        if (count($v) > 0) {
            $v->title = $variant['title'];
            $v->price = $variant['price'];
            $v->sku = $variant['sku'];
            $v->position = $variant['position'];
            $v->grams = $variant['grams'];
            $v->inventory_policy = $variant['inventory_policy'];
            $v->compare_at_price = $variant['compare_at_price'];
            $v->fulfillment_service = $variant['fulfillment_service'];
            $v->inventory_management = $variant['inventory_management'];
            $v->option1 = $variant['option1'];
            $v->option2 = $variant['option2'];
            $v->option3 = $variant['option3'];
            $v->taxable = $variant['taxable'];
            $v->barcode = $variant['barcode'];
            $v->image_id = $variant['image_id'];
            $v->inventory_quantity = $variant['inventory_quantity'];
            $v->weight = $variant['weight'];
            $v->weight_unit = $variant['weight_unit'];
            $v->old_inventory_quantity = $variant['old_inventory_quantity'];
            $v->requires_shipping = $variant['requires_shipping'];
            $v->created_at = Carbon::parse($variant['created_at']);
            $v->updated_at = Carbon::parse($variant['updated_at']);
            $v->save();
        } else {
            $va = new Variant();
            $va->id = $variant['id'];
            $va->product_id = $variant['product_id'];
            $va->title = $variant['title'];
            $va->price = $variant['price'];
            $va->sku = $variant['sku'];
            $va->position = $variant['position'];
            $va->grams = $variant['grams'];
            $va->inventory_policy = $variant['inventory_policy'];
            $va->compare_at_price = $variant['compare_at_price'];
            $va->fulfillment_service = $variant['fulfillment_service'];
            $va->inventory_management = $variant['inventory_management'];
            $va->option1 = $variant['option1'];
            $va->option2 = $variant['option2'];
            $va->option3 = $variant['option3'];
            $va->taxable = $variant['taxable'];
            $va->barcode = $variant['barcode'];
            $va->image_id = $variant['image_id'];
            $va->inventory_quantity = $variant['inventory_quantity'];
            $va->weight = $variant['weight'];
            $va->weight_unit = $variant['weight_unit'];
            $va->old_inventory_quantity = $variant['old_inventory_quantity'];
            $va->requires_shipping = $variant['requires_shipping'];
            $va->created_at = Carbon::parse($variant['created_at']);
            $va->updated_at = Carbon::parse($variant['updated_at']);
            $va->shop = $shop;
            $va->save();
        }
    }

    public static function search($id)
    {
        return Variant::findOrFail($id);
    }
}
