<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sku_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->unsignedBigInteger('product_id')->default(0)->nullable(false)->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->default(0)->nullable(false)->comment('SKU ID');
            $table->unsignedBigInteger('property_id')->default(0)->nullable(false)->comment('属性ID');
            $table->unsignedBigInteger('selector_id')->default(0)->nullable(false)->comment('属性值ID');
            $table->timestamps();
            $table->index(['sku_id'], 'sku-id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sku_properties');
    }
}
