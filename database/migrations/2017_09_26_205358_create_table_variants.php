<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable();
            $table->text('title')->nullable();
            $table->double('price')->nullable();
            $table->text('sku')->nullable();
            $table->integer('position')->nullable();
            $table->string('inventory_policy')->nullable();
            $table->double('compare_at_price')->nullable();
            $table->string('fulfillment_service')->nullable();
            $table->string('inventory_management')->nullable();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->boolean('taxable')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('grams')->nullable();
            $table->bigInteger('image_id')->nullable();
            $table->integer('inventory_quantity')->nullable();
            $table->double('weight')->nullable();
            $table->string('weight_unit')->nullable();
            $table->integer('old_inventory_quantity')->nullable();
            $table->boolean('requires_shipping')->nullable();
            $table->double('sold_units')->nullable();
            $table->double('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('variants');
    }
}
