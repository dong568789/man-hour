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
            $table->unsignedInteger('apply_uid')->index()->comment="申请人";
            $table->unsignedInteger('pid')->index()->comment="申请项目";
            $table->unsignedInteger('uid')->comment="申请成员";
            $table->unsignedInteger('to_pid')->index()->comment="被申请项目";
            $table->string('message')->nullable()->comment="消息";
            $table->unsignedInteger('apply_status')->comment="申请状态";
            $table->unsignedInteger('operator_uid')->comment="操作人";
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
