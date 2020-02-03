<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnCreaterIdToEditorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
        });

        Schema::table('category_properties', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
        });

        Schema::table('category_property_selectors', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
        });

        Schema::table('category_selectors', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
        });

        Schema::table('expresses', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
        });

        Schema::table('shop_uploads', function (Blueprint $table) {
            $table->renameColumn('creater_id', 'editor_id')->comment('最后更新用户ID');
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
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });

        Schema::table('category_properties', function (Blueprint $table) {
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });

        Schema::table('category_property_selectors', function (Blueprint $table) {
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });

        Schema::table('category_selectors', function (Blueprint $table) {
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });

        Schema::table('expresses', function (Blueprint $table) {
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });

        Schema::table('shop_uploads', function (Blueprint $table) {
            $table->renameColumn('editor_id', 'creater_id')->comment('创建管理员ID');
        });
    }
}
