<?php

namespace App\Models;

use App\Model;

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
