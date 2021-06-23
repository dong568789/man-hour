<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Repositories\ProjectStatRepository;

class StatHour implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue, SerializesModels;

	protected $pid;

	public function __construct(int $pid = 0)
	{
		$this->pid = $pid;
	}

	public function handle()
	{
        $psRepo = new ProjectStatRepository();
        $psRepo->stat($this->pid);
	}
}
