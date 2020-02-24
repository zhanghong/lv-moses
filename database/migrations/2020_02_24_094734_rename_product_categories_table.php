<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('product_categories', 'product_groups');
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('category_id', 'group_id');
            $table->renameColumn('base_category_id', 'category_id');
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
            $table->renameColumn('category_id', 'base_category_id');
            $table->renameColumn('group_id', 'category_id', );
        });
        Schema::rename('product_groups', 'product_categories');
    }
}
