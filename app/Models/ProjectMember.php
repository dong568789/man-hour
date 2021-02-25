<?php

namespace App\Models;

use App\Model;

class ProjectMember extends Model
{
    protected $guarded = ['id'];

    protected $casts = [

    ];

    public function member()
    {
        return $this->belongsTo("App\\User", 'uid', 'id');
    }

    public function project()
    {
        return $this->belongsTo("App\\Models\\Project", 'pid', 'id');
    }
}

