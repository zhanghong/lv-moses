<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('系统分类ID');
            $table->string('name', 20)->default('')->nullable(false)->comment('名称');
            $table->string('icon_url')->default('')->comment('Icon URL');
            $table->unsignedBigInteger('parent_id')->default(0)->nullable(false)->comment('父ID');
            $table->boolean('is_directory')->default(false)->nullable(false)->comment('是否有子节点');
            $table->unsignedInteger('level')->default(0)->nullable(false)->comment('层级');
            $table->string('path')->default('')->comment('祖先IDs');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
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
        Schema::dropIfExists('shop_categories');
    }
}
