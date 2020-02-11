<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Product\Sku;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('product_id')->default(0)->nullable(false)->comment('商品ID');
            $table->unsignedBigInteger('main_image_id')->default(0)->nullable(false)->comment('主图片ID');
            $table->string('main_image_url', 255)->default('')->comment('主图片路径');
            $table->unsignedDecimal('market_price', 10, 2)->default(0.0)->comment('市场价');
            $table->unsignedDecimal('sell_price', 10, 2)->default(0.0)->comment('售价');
            $table->unsignedDecimal('cost_price', 10, 2)->default(0.0)->comment('成本价');
            $table->unsignedDecimal('integral', 10, 2)->default(0.0)->comment('获得积分');
            $table->unsignedInteger('stock')->default(0)->nullable(false)->comment('库存量');
            $table->text('properties_content')->comment('属性值');
            $table->integer('order')->default(Sku::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['product_id'], 'product-id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_skus');
    }
}
