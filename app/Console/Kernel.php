<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $last2 = Carbon::today()->subDays(2);

        $logPath = storage_path('logs/tasks.log');

        // 新建日志文件，并且改变其所有者为nobody
        // 因为crontab运行用户是root，所以调度任务生成的log将会是root用户，
        // 此举会造成网页端的无法写入日志，故而先生成日志并且改变用户为宜
        $schedule->call(function() use($today) {
            $logPath = storage_path('logs/laravel-'.$today->format('Y-m-d').'.log');
            if (!file_exists($logPath))
                touch($logPath);

            chown($logPath, 'nobody');
            chgrp($logPath, 'nobody');

        })->dailyAt('00:00');

        /**
         * 每天统计项目成本
         */
        $schedule->command('project:stat')
            ->dailyAt('00:10')
            ->appendOutputTo($logPath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
