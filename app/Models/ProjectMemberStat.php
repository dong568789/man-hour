<?php

namespace App\Models;

use Auth;
use App\Model;
use App\Tools\Helper;

class ProjectMemberStat extends Model
{
    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo("App\\User", 'uid', 'id');
    }

    public function project()
    {
        return $this->belongsTo("App\\Models\\Project", 'pid', 'id');
    }
}

ProjectMemberStat::retrieved(function ($user) {
    $auth = Auth::user();
    if(!empty($auth) && (Helper::isPm($auth) || Helper::isMember($auth))) {
        $user->addHidden(['cost']);
    }
});
