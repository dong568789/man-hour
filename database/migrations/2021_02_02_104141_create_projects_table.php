<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->comment="项目名称";
            $table->string('detail')->nullable()->comment="描述";
            $table->unsignedInteger('cover_id')->nullable()->comment="封面";
            $table->unsignedInteger('project_status')->index()->comment="项目进度";
            $table->timestamp('end_at')->nullable()->comment="项目结束时间";
            $table->unsignedInteger('pm_uid')->index()->comment="pm";

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('project_members', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('uid')->comment="成员";
            $table->string('pid')->comment="项目";
            $table->unsignedInteger('member_status')->comment="人员状态";
            $table->integer('hour')->default(0)->comment="工时";

            $table->unique(['uid', 'pid']);
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
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_members');
    }
}
