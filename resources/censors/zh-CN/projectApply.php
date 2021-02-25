<?php

return [
    'store' => [
        'status' => [
            'name' => '状态',
            'rules' => 'numeric'
        ],
        'mark' => [
            'name' => '拒绝理由',
            'rules' => 'nullable'
        ],
    ],
    'apply' => [
        'dates' => [
            'name' => '申报时间',
            'rules' => 'required|array'
        ],
        'pid' => [
            'name' => '项目',
            'rules' => 'required|numeric'
        ],
    ]
];
