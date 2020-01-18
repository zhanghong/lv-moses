<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('管理员ID');
            $table->string('name', 30)->default('')->nullable(false)->comment('名称');
            $table->unsignedBigInteger('parent_id')->default(0)->nullable(false)->comment('父ID');
            $table->boolean('is_directory')->default(false)->nullable(false)->comment('是否有子节点');
            $table->unsignedInteger('level')->default(0)->nullable(false)->comment('层级');
            $table->string('path', 30)->default('')->comment('祖先IDs');
            $table->integer('order')->default(0)->nullable(false)->comment('排序编号');
            $table->boolean('is_enabled')->default(true)->nullable(false)->comment('是否启用');
            $table->string('outer_name', 6)->default('')->comment('来源名称');
            $table->string('outer_key', 10)->default('')->comment('来源主键');
            $table->string('outer_code', 20)->default('')->comment('来源编号');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['parent_id'], 'idx-by-pid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
