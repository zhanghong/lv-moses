<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Product\Brand;

class CreateShopBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->string('name', 50)->default('')->nullable(false)->comment('名称');
            $table->string('logo_url')->default('')->comment('Logo URL');
            $table->string('description')->default('')->comment('品牌介绍');
            $table->integer('order')->default(Brand::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
            $table->boolean('is_enabled')->default(true)->nullable(false)->comment('是否启用');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_brands');
    }
}
