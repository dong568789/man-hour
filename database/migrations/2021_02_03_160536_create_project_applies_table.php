<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_applies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('uid')->index()->comment="申请人";
            $table->integer('pid')->index()->comment="申请项目";
            $table->json('dates')->comment="工时";
            $table->string('message')->nullable()->comment="消息";
            $table->string('mark')->nullable()->comment="备注";
            $table->unsignedInteger('apply_status')->comment="申请状态";
            $table->unsignedInteger('operator_uid')->nullable()->default(0)->comment="操作人";
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
        Schema::dropIfExists('project_applies');
    }
}
