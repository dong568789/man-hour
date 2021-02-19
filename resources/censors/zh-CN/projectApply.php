<?php

return [
    'store' => [
        'status' => [
            'name' => '状态',
            'rules' => 'numeric'
        ],
    ],
    'apply' => [
        'member_ids' => [
            'name' => '成员',
            'rules' => 'required|array'
        ],
        'pid' => [
            'name' => '项目',
            'rules' => 'required|numeric'
        ],
    ]
];
