<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->string('name')->default('')->limit(50)->nullable(false)->comment('名称');
            $table->string('logo_url')->default('')->limit(255)->comment('Logo URL');
            $table->string('description')->default('')->limit(255)->comment('品牌介绍');
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
        Schema::dropIfExists('shop_brands');
    }
}
