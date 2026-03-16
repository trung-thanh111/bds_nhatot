<?php
return [
    'module' => [
        [
            'title' => 'Dashboard',
            'icon' => 'fa fa-th-large',
            'name' => ['dashboard'],
            'route' => 'dashboard/index',
            'class' => 'special'
        ],
        [
            'title' => 'Bất Động Sản',
            'icon' => 'fa fa-home',
            'name' => ['property', 'property_facility', 'floorplan', 'floorplan_room', 'location_highlight', 'visit_request', 'agent'],
            'subModule' => [
                [
                    'title' => 'Bất Động Sản',
                    'route' => 'property/index'
                ],
                [
                    'title' => 'Tiện Ích BĐS',
                    'route' => 'property_facility/index'
                ],
                [
                    'title' => 'Mặt Bằng',
                    'route' => 'floorplan/index'
                ],
                [
                    'title' => 'Phòng Mặt Bằng',
                    'route' => 'floorplan_room/index'
                ],
                [
                    'title' => 'Tiện Ích Lân Cận',
                    'route' => 'location_highlight/index'
                ]
            ]
        ],
        [
            'title' => 'Bài viết',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Bài viết',
                    'route' => 'post/index'
                ],
                [
                    'title' => 'Nhóm bài viết',
                    'route' => 'post/catalogue/index'
                ],
            ]
        ],
        [
            'title' => 'Thư viện ảnh',
            'icon' => 'fa fa-picture-o',
            'name' => ['gallery', 'gallery_catalogue'],
            'subModule' => [
                [
                    'title' => 'Danh sách',
                    'route' => 'gallery/index'
                ],
                [
                    'title' => 'Nhóm thư viện',
                    'route' => 'gallery/catalogue/index'
                ]
            ]
        ],
        [
            'title' => 'Nhân viên môi giới',
            'icon' => 'fa fa-users',
            'name' => ['agent'],
            'subModule' => [
                [
                    'title' => 'Danh sách nhân viên',
                    'route' => 'agent/index'
                ],
            ]
        ],
        [
            'title' => 'QL Liên Hệ',
            'icon' => 'fa fa-phone-square',
            'name' => ['contacts'],
            'subModule' => [
                [
                    'title' => 'QL Liên Hệ',
                    'route' => 'visit_request/index'
                ]
            ]
        ],
        [
            'title' => 'QL Menu',
            'icon' => 'fa fa-bars',
            'name' => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Menu',
                    'route' => 'menu/index'
                ],
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system/index'
                ],

            ]
        ]
    ],
];
