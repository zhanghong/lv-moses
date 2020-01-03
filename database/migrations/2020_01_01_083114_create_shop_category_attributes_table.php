<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCategoryAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_category_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('系统分类ID');
            $table->string('name', 20)->default('')->nullable(false)->comment('名称');
            $table->string('type', 6)->default('')->nullable(false)->comment('属性类型');
            $table->string('values', 500)->default('')->comment('属性值列表');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
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
        Schema::dropIfExists('shop_category_attributes');
    }
}
