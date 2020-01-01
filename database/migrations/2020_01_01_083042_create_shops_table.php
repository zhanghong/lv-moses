<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creater_id')->default(0)->nullable(false)->comment('创建管理员ID');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('manager_id')->default(0)->nullable(false)->comment('负责用户ID');
            $table->string('name')->default('')->limit(100)->nullable(false)->comment('名称');
            $table->string('main_image_url')->default('')->limit(255)->comment('Logo URL');
            $table->unsignedInteger('store_count')->default(0)->comment('门店数量');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->boolean('is_default')->default(false)->nullable(false)->comment('是否默认店铺');
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
        Schema::dropIfExists('shops');
    }
}
