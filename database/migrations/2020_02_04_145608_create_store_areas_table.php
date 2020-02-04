<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('最后更新用户ID');
            $table->unsignedBigInteger('store_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->unsignedBigInteger('province_id')->default(0)->nullable(false)->comment('所在省');
            $table->unsignedBigInteger('city_id')->default(0)->nullable(false)->comment('所在城市');
            $table->unsignedBigInteger('district_id')->default(0)->nullable(false)->comment('所在区县');
            $table->unsignedBigInteger('street_id')->default(0)->nullable(false)->comment('所在街道');
            $table->string('path', 255)->default('')->comment('所辖区域路径');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->index(['store_id', 'order'], 'store-id-order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_areas');
    }
}
