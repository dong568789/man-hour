<?php

namespace App\Models;

use App\Model;

class ProjectApply extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'dates' => 'array',
        'apply_status' => 'catalog'
    ];


    public function project()
    {
        return $this->belongsTo("App\\Models\\Project", 'pid', 'id');
    }

    public function member()
    {
        return $this->belongsTo("App\\User", 'uid', 'id');
    }
}
