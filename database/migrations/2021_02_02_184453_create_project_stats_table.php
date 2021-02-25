<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_stats', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pid')->index()->comment="";
            $table->decimal('cost')->default(0)->comment="成本";
            $table->decimal('day_cost')->default(0)->comment="日成本";
            $table->decimal('avg_cost')->default(0)->comment="平均成本";
            $table->string('mark')->nullable()->comment="备注";
            $table->timestamps();
        });

        Schema::create('project_member_stats', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pid')->index()->comment="项目id";
            $table->unsignedInteger('uid')->index()->comment="成员id";
            $table->integer('hour')->default(0)->comment="工时";
            $table->integer('cost')->default(0)->comment="成本";
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
        Schema::dropIfExists('project_stats');
        Schema::dropIfExists('project_member_stats');
        Schema::dropIfExists('project_member_logs');
    }
}
