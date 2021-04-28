<?php

namespace App\Models;

use Auth;
use App\Model;
use App\Tools\Helper;

class ProjectStat extends Model
{
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo("App\\Models\\Project", 'pid', 'id');
    }

    public function members()
    {
        return $this->hasMany("App\\Models\\ProjectMemberStat", 'pid', 'pid');
    }
}

ProjectStat::retrieved(function ($user) {
    $auth = Auth::user();
    if(!empty($auth) && (Helper::isPm($auth) || Helper::isMember($auth))) {
        $user->addHidden(['cost', 'day_cost']);
    }
});
