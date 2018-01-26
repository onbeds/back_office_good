<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRulesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_details', function (Blueprint $table) {
            Schema::create('rules_details', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('rule_id');
                $table->integer('nivel');
                $table->integer('comision_puntos');
                $table->string('vendedores_directos');
                $table->timestamps();

                $table->foreign('rule_id')->references('id')->on('rules');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rules_details');
    }
}
