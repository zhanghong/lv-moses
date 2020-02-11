<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Product\Product;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('base_category_id')->default(0)->nullable(false)->comment('基础类目ID');
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('分类ID');
            $table->unsignedBigInteger('brand_id')->default(0)->nullable(false)->comment('品牌ID');
            $table->string('type', 8)->default(Product::TYPE_NORMAL)->nullable(false)->comment('类型');
            $table->string('title', 100)->default('')->nullable(false)->comment('标题');
            $table->string('long_title', 255)->default('')->nullable(false)->comment('长标题');
            $table->string('main_image_url', 255)->default('')->comment('主图片路径');
            $table->unsignedSmallInteger('flags')->default(0)->comment('状态位');
            $table->string('description', 5000)->default('')->comment('描述');
            $table->unsignedDecimal('rating', 5, 2)->default(0.0)->comment('总体评分');
            $table->unsignedInteger('fiction_count')->default(0)->nullable(false)->comment('虚拟数量');
            $table->unsignedInteger('sold_count')->default(0)->nullable(false)->comment('已售数量');
            $table->unsignedDecimal('min_market_price', 10, 2)->default(0.0)->comment('最低市场价');
            $table->unsignedDecimal('max_market_price', 10, 2)->default(0.0)->comment('最高市场价');
            $table->unsignedDecimal('min_sell_price', 10, 2)->default(0.0)->comment('最低售价');
            $table->unsignedDecimal('max_sell_price', 10, 2)->default(0.0)->comment('最高售价');
            $table->integer('order')->default(Product::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['shop_id', 'category_id'], 'shopid-catid');
        });

        Schema::create('product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->default(0)->nullable(false)->comment('商品ID');
            $table->unsignedBigInteger('main_image_id')->default(0)->nullable(false)->comment('主图片ID');
            $table->unsignedDecimal('integral', 10, 2)->default(0.0)->comment('获得积分');
            $table->text('introduce')->comment('详细介绍');
            $table->index(['product_id'], 'product_id');
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_details');
    }
}
