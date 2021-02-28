<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsSinglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_singles', function (Blueprint $table) {
            $table->id();
            $table->string('s_id')->comment('统计id');
            $table->string('shop_name')->comment('商店名称');
            $table->string('g_name')->comment('商品名称');
            $table->string('color')->comment('颜色');
            $table->string('size')->comment('码数');
            $table->integer('count')->default(0)->comment('数量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics_singles');
    }
}
