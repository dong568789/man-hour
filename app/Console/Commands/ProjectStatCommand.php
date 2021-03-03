<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

use App\Repositories\ProjectStatRepository;

class ProjectStatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:stat {--pid= : project id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'stat project member man hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pid = $this->option('pid') ?: 0;
        $psRepo = new ProjectStatRepository();

        $psRepo->stat($pid);
    }
}
