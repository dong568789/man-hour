<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('uid')->index()->comment="收件人";
            $table->string('content')->comment="";
            $table->string('type')->comment="类型";
            $table->tinyInteger('read')->default(0)->comment="1已读";
            $table->timestamps();

            $table->index(['uid', 'read']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
