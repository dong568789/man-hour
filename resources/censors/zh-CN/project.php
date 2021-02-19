<?php

return [
    'store' => [
        'name' => [
            'name' => '项目名称',
            'rules' => 'required'
        ],
        'detail' => [
            'name' => '项目描述',
            'rules' => 'nullable'
        ],
        'cover_id' => [
            'name' => '封面',
            'rules' => 'nullable|numeric'
        ],
        'project_status' => [
            'name' => '项目状态',
            'rules' => 'required|not_zero|catalog:status.project_status'
        ],
        'end_at' => [
            'name' => '结束时间',
            'rules' => ''
        ],
        'pm_uid' => [
            'name' => 'PM',
            'rules' => 'nullable|numeric'
        ],
        'member_ids' => [
            'name' => '项目成员',
            'rules' => 'nullable|array'
        ],
    ],
];
