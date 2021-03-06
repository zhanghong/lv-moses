<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Category\Category;

class CreateBaseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creater_id')->default(0)->nullable(false)->comment('创建管理员ID');
            $table->string('name', 20)->default('')->nullable(false)->comment('名称');
            $table->string('icon_url')->default('')->comment('Icon URL');
            $table->unsignedBigInteger('parent_id')->default(0)->nullable(false)->comment('父ID');
            $table->boolean('is_directory')->default(false)->nullable(false)->comment('是否有子节点');
            $table->unsignedInteger('level')->default(0)->nullable(false)->comment('层级');
            $table->string('path')->default('')->comment('祖先IDs');
            $table->integer('order')->default(Category::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
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
        Schema::dropIfExists('categories');
    }
}
