<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Base\Express;

class CreateBaseExpressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creater_id')->default(0)->nullable(false)->comment('创建管理员ID');
            $table->string('code', 30)->default('')->nullable(false)->comment('名称');
            $table->string('name', 30)->default('')->nullable(false)->comment('名称');
            $table->string('logo_url')->default('')->comment('Logo 图片');
            $table->integer('order')->default(Express::ORDER_DEFAULT)->nullable(false)->comment('排序编号');
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
        Schema::dropIfExists('expresses');
    }
}
