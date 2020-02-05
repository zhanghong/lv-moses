<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShopIdToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('所属店铺ID')->after('editor_id');
        });

        Schema::table('category_selectors', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('所属店铺ID')->after('editor_id');
        });

        Schema::table('category_property_selectors', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('所属店铺ID')->after('editor_id');
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
            $table->dropColumn('shop_id');
        });

        Schema::table('category_selectors', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });

        Schema::table('category_property_selectors', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });
    }
}
