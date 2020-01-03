<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creater_id')->default(0)->nullable(false)->comment('创建用户ID');
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->string('mine_type', 30)->default('')->comment('文件类型');
            $table->unsignedInteger('file_size')->default(0)->comment('文件大小');
            $table->string('origin_name')->default('')->comment('文件名');
            $table->string('file_url')->default('')->nullable(false)->comment('文件URL');
            $table->string('attach_type', 20)->default('')->nullable(false)->comment('附件类型');
            $table->string('attachable_type', 20)->default('')->nullable(false)->comment('所属实例类型');
            $table->unsignedBigInteger('attachable_id')->default(0)->nullable(false)->comment('创建用户ID');
            $table->timestamps();
            $table->index(['shop_id', 'attach_type', 'attachable_type', 'attachable_id'], 'sid-atype-obj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_uploads');
    }
}
