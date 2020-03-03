<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_skus')->default(false)->nullable(false)->comment('是否拥有Skus')->after('sold_count');
            $table->unsignedDecimal('cost_price', 10, 2)->default(0.0)->comment('成本价')->after('max_sell_price');
            $table->unsignedInteger('stock')->default(0)->nullable(false)->comment('库存量')->after('cost_price');
            $table->boolean('is_published')->default(false)->nullable(false)->comment('是否已发布')->after('max_sell_price');
            $table->dateTime('published_at')->nullable(true)->comment('发布时间')->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_skus');
            $table->dropColumn('sold_count');
            $table->dropColumn('stock');
            $table->dropColumn('is_published');
            $table->dropColumn('published_at');
        });
    }
}
