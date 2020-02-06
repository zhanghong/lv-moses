<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_property', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('最后更新用户ID');
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('分类ID');
            $table->unsignedBigInteger('property_id')->default(0)->nullable(false)->comment('属性ID');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->index(['category_id', 'property_id'], 'cid-pid');
        });

        Schema::table('category_properties', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->default(0)->nullable(false)->comment('分类ID');
        });

        Schema::dropIfExists('category_property');
    }
}
