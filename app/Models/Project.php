<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use SoftDeletes,AttachmentCastTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'project_status' => 'catalog',
        'cover_id' => 'attachment',
    ];

    public function pm()
    {
        return $this->belongsTo("App\\User", 'pm_uid', 'id');
    }

    public function members()
    {
        return $this->belongsToMany("App\\User", 'project_members', 'pid', 'uid')
            ->wherePivotIn('member_status', [catalog_search('status.member_status.going', 'id'), catalog_search('status.member_status.normal', 'id')])
            ->withPivot('member_status', 'hour', 'id')
            ->withTimestamps();
    }

    public function scopeOfMember(Builder $builder, $uid)
    {
        return $builder->join('project_members', 'project_members.pid', '=', 'projects.id', 'LEFT')
            ->where('project_members.member_status', '!=', catalog_search('status.member_status.deleted', 'id'))
            ->select(['projects.*', 'project_members.id as pmid', 'project_members.uid', 'project_members.pid', 'project_members.member_status', 'project_members.hour'])
            ->where('project_members.uid', $uid);
    }
}

