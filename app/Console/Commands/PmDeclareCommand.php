<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

use App\Repositories\ProjectMemberRepository;

class PmDeclareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pm:declare {--date=}';

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
        $date = $this->option('date');
        $at = Carbon::parse($date);

        $psRepo = new ProjectMemberRepository();
        $psRepo->pmDeclare($at);
    }
}
