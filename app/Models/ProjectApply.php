<?php

namespace App\Models;

use App\Model;

class ProjectApply extends Model
{
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo("App\\Models\\Project", 'pid', 'id');
    }
}
