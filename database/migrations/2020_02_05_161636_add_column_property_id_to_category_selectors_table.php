<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPropertyIdToCategorySelectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_selectors', function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->default(0)->nullable(false)->comment('分类属性ID')->after('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_selectors', function (Blueprint $table) {
            $table->dropColumn('property_id');
        });
    }
}
