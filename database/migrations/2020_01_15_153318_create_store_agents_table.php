<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAgentsTable extends Migration
{
    public function up()
    {
        Schema::create('store_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->string('name', 100)->default('')->nullable(false)->comment('名称');
            $table->string('contact_name', 30)->default('')->nullable(false)->comment('联系人');
            $table->string('contact_phone', 30)->default('')->nullable(false)->comment('联系电话');
            $table->string('contact_address', 255)->default('')->comment('联系地址');
            $table->integer('store_count')->default(0)->comment('门店数量');
            $table->boolean('is_enabled')->default(true)->nullable(false)->comment('是否启用');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['shop_id'], 'shop-id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_agents');
    }
}
