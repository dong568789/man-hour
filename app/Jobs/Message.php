<?php

namespace App\Jobs;

use App\Repositories\UserRepository;
use App\Tools\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

use App\Repositories\MessageRepository;

class Message implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue, SerializesModels;

	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function handle()
	{
	    $username = "caiwu";
	    $uRepo = new UserRepository();

        $role = $this->user->roles->first();
        $roleid = array_get($role, 'id');

	    $user = $uRepo->findByUsername($username);

	    $code = md5($user->id . microtime(true));

        $mRepo = new MessageRepository();
        $mRepo->store([
            'uid' => $user->id,
            'content' => "有新的项目成员【" . $this->user->realname . "】注册，点击<a href='" . url('admin/message/read'). "?type=register&role=" .$roleid. "&code="
                . $code ."'>前往处理</a>。",
            'type' => 'register',
            'code' => $code,
            'read' => 0
        ]);
	}
}
