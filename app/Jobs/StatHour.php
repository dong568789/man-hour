<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Addons\Core\Models\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Plugins\Chat\App\Tools\ChatMatch;

use App\Repositories\ProjectStatRepository;

class StatHour implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue, SerializesModels;

	protected $pid;

	public function __construct($pid)
	{
		$this->pid = $pid;
	}

	public function handle()
	{
        $psRepo = new ProjectStatRepository();
        $psRepo->stat($this->pid);
	}


}
