<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('agent_id')->default(0)->nullable(false)->comment('经销商ID');
            $table->unsignedBigInteger('manager_id')->default(0)->nullable(false)->comment('负责人ID');
            $table->string('name', 100)->default('')->nullable(false)->comment('名称');
            $table->string('auth_no', 30)->default('')->nullable(false)->comment('授权码');
            $table->unsignedBigInteger('main_image_id')->default(0)->nullable(false)->comment('主图片ID');
            $table->string('main_image_url', 255)->default('')->nullable(false)->comment('主图片URL');
            $table->unsignedBigInteger('area_id')->default(0)->nullable(false)->comment('所在区县');
            $table->string('full_address', 255)->default('')->nullable(false)->comment('详细地址');
            $table->unsignedDecimal('longitude', 10, 7)->default(0)->comment('详细地址经度');
            $table->unsignedDecimal('latitude', 10, 7)->default(0)->comment('详细地址纬度');
            $table->time('work_start_time')->default('00:00:00')->nullable(false)->comment('是否启用');
            $table->time('work_end_time')->default('24:00:00')->nullable(false)->comment('是否启用');
            $table->boolean('is_enabled')->default(true)->nullable(false)->comment('是否启用');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['shop_id'], 'shop-id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
