<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Category\Category;

class AddShopIdToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->default(0)->nullable(false)->comment('所属店铺ID')->after('editor_id');
            $table->unsignedSmallInteger('type')->default(Category::TYPE_BASE)->comment('分类类型')->after('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('shop_id');
            $table->dropColumn('type');
        });
    }
}
