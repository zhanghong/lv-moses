<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('更新用户ID');
            $table->unsignedBigInteger('store_id')->default(0)->nullable(false)->comment('店铺ID');
            $table->string('contact_name', 30)->default('')->nullable(false)->comment('联系人');
            $table->string('contact_phone', 30)->default('')->nullable(false)->comment('联系电话');
            $table->string('address', 255)->default('')->nullable(false)->comment('详细地址');
            $table->string('zip_code', 6)->default('')->nullable(false)->comment('邮编');
            $table->unsignedInteger('staff_count')->default(0)->nullable(false)->comment('员工人数');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['store_id'], 'store-id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_configs');
    }
}
