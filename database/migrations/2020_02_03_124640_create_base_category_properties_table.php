<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Category\Property;

class CreateBaseCategoryPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creater_id')->default(0)->nullable(false)->comment('创建管理员ID');
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('分类ID');
            $table->string('name', 30)->default('')->nullable(false)->comment('名称');
            $table->string('type', 10)->default('')->nullable(false)->comment('属性类型');
            $table->string('choice', 10)->default('')->nullable(false)->comment('显示格式');
            $table->string('value_ids', 1000)->default('')->comment('属性值IDs');
            $table->string('outer_name', 6)->default('')->comment('来源名称');
            $table->string('outer_key', 10)->default('')->comment('来源主键');
            $table->string('outer_cid', 10)->default('')->comment('来源分类ID');
            $table->string('outer_selector_ids', 1000)->default('')->comment('属性值IDs');
            $table->integer('order')->default(Property::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
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
        Schema::dropIfExists('category_properties');
    }
}
