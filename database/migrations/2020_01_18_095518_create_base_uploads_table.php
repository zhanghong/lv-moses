<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Base\Upload;

class CreateBaseUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('editor_id')->default(0)->nullable(false)->comment('创建用户ID');
            $table->string('file_path')->default('')->nullable(false)->comment('文件URL');
            $table->unsignedBigInteger('file_size')->default(0)->comment('文件大小');
            $table->string('origin_name')->default('')->comment('文件名');
            $table->string('mime_type', 30)->default('')->comment('文件类型');
            $table->boolean('is_image')->default(0)->comment('是否是图片');
            $table->unsignedInteger('file_width')->default(0)->comment('图片宽度');
            $table->unsignedInteger('file_height')->default(0)->comment('图片高度');
            $table->string('attach_type', 20)->default('')->nullable(false)->comment('附件类型');
            $table->string('ownable_type', 20)->default('')->nullable(false)->comment('拥有者类型');
            $table->unsignedBigInteger('ownable_id')->default(0)->nullable(false)->comment('拥有者ID');
            $table->string('attachable_type', 20)->default('')->nullable(false)->comment('所属实例类型');
            $table->unsignedBigInteger('attachable_id')->default(0)->nullable(false)->comment('所属实例ID');
            $table->integer('order')->default(Upload::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
            $table->timestamps();
            $table->index(['ownable_type', 'ownable_id', 'attach_type'], 'own-atype');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
