<?php

namespace App\Models;

use App\Model;

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
