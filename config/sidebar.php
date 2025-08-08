<?php
return [
    [
        'name' => 'Sản phẩm',
        'list-check' => ['attribute','category','keyword','product','comment','rating'],
        'icon' => 'fa fa-database',
		'level'  => [1,2],
        'sub'  => [
            [
                'name'  => 'Thông tin',
                'namespace' => 'attribute',
                'route' => 'admin.attribute.index',
                'icon'  => 'fa fa-key',
				'level'  => [1,2],
            ],
            [
                'name'  => 'Danh mục',
                'namespace' => 'category',
                'route' => 'admin.category.index',
                'icon'  => 'fa fa-edit',
				'level'  => [1,2],
            ],
//            [
//                'name'  => 'Từ khoá',
//                'namespace' => 'keyword',
//                'route' => 'admin.keyword.index',
//                'icon'  => 'fa fa-key',
//				'level'  => [1,2],
//            ],
            [
                'name'  => 'Sản phẩm',
                'namespace' => 'product',
                'route' => 'admin.product.index',
                'icon'  => 'fa fa-database',
				'level'  => [1,2],
            ],
			[
                'name'  => 'Đánh giá',
                'namespace' => 'rating',
                'route' => 'admin.rating.index',
                'icon'  => 'fa fa-star',
				'level'  => [1,2],
            ],
			[
                'name'  => 'Bình luận',
                'namespace' => 'comment',
                'route' => 'admin.comment.index',
                'icon'  => 'fa fa-star',
				'level'  => [1,2],
            ],
            [
                'name'  => 'Mã giảm giá',
                'namespace' => 'discount-code',
                'route' => 'admin.discount.code.index',
                'icon'  => 'fa fa-fw fa-arrow-circle-down',
                'level'  => [1,2],
            ],
        ]
    ],
    [
        'name' => 'Tin tức',
        'list-check' => ['menu','article'],
        'icon' => 'fa fa-edit',
		'level'  => [1,2],
        'sub'  => [
            [
                'name'  => 'Menu',
                'namespace' => 'menu',
                'route' => 'admin.menu.index',
                'icon'  => 'fa fa-key',
				'level'  => [1,2],
            ],
            [
                'name'  => 'Bài viết',
                'namespace' => 'article',
                'route' => 'admin.article.index',
                'icon'  => 'fa fa-key',
				'level'  => [1,2],
            ],
        ]
    ],
	[
		'name' => 'Đối tác',
		'list-check' => ['user','ncc'],
		'icon' => 'fa fa-user',
		'level'  => [1,2],
		'sub'  => [
			
			[
				'name'  => 'Nhà cung cấp',
				'route' => 'admin.ncc.index',
				'namespace' => 'user',
				'icon'  => 'fa fa-users',
				'level'  => [1,2],
			],
            [
               'name'  => 'Liên hệ',
               'namespace' => 'contact',
               'route' => 'admin.contact',
               'icon'  => 'fa fa-star',
               'level'  => [1,2],
           ],
		]
	],
//    [
//        'name' => 'Account',
//        'list-check' => ['user','rating','comment','contact'],
//        'icon' => 'fa fa-user',
//        'sub'  => [
//            [
//                'name'  => 'User',
//                'route' => 'admin.user.index',
//                'namespace' => 'user',
//                'icon'  => 'fa fa-user'
//            ],
//            [
//                'name'  => 'Rating',
//                'namespace' => 'rating',
//                'route' => 'admin.rating.index',
//                'icon'  => 'fa fa-star'
//            ],
//            [
//                'name'  => 'Comment',
//                'namespace' => 'comment',
//                'route' => 'admin.comment.index',
//                'icon'  => 'fa fa-star'
//            ],

//        ]
//    ],
    [
        'name' => 'Đơn hàng',
        'list-check' => ['transaction'],
        'icon' => 'fa-shopping-cart',
		'level'  => [1,2],
        'sub'  => [
            [
                'name'  => 'Danh sách',
                'namespace' => 'transaction',
                'route' => 'admin.transaction.index',
                'icon'  => 'fa-opencart',
				'level'  => [1,2],
            ]
        ]
    ],
	[
        'name' => 'Kho',
        'list-check' => ['inventory','import','export'],
        'icon' => 'fa-shopping-cart',
		'level'  => [1,2],
        'sub'  => [
            [
                'name'  => 'Nhập kho',
                'namespace' => 'import',
                'route' => 'admin.inventory.warehousing',
                'icon'  => 'fa-opencart',
				'level'  => [1,2],
            ],
			[
				'name'  => 'Xuất kho',
				'namespace' => 'export',
				'route' => 'admin.export.out_of_stock',
				'icon'  => 'fa-opencart',
				'level'  => [1,2],
			],
        ]
    ],
//    [
//        'name' => 'SystemPay',
//        'list-check' => ['pay-in'],
//        'icon' => 'fa  fa-usd',
//        'sub'  => [
//            [
//                'name'  => 'Nạp tiền',
//                'route' => 'admin.system_pay_in.index',
//                'namespace' => 'pay-in',
//                'icon'  => 'fa fa-money'
//            ]
//        ]
//    ],
    [
        'name'  => 'System',
        'label' => 'true'
    ],
	[
		'name' => 'Hệ thống',
		'list-check' => ['slide','account_admin','event','page-static','statistical'],
		'icon' => 'fa  fa-usd',
		'level'  => [1],
		'sub'  => [
			[
				'name'  => 'Tài khoản',
				'route' => 'admin.account_admin.index',
				'namespace' => 'pay-in',
				'level'  => [1],
				'icon'  => 'fa-circle-o'
			],
			[
				'name'  => 'Slide',
				'route' => 'admin.slide.index',
				'namespace' => 'pay-in',
				'level'  => [1],
				'icon'  => 'fa-circle-o'
			],
			[
				'name'  => 'Sự kiện',
				'route' => 'admin.event.index',
				'namespace' => 'pay-in',
				'level'  => [1],
				'icon'  => 'fa-circle-o'
			],
			[
				'name'  => 'Thống kê',
				'route' => 'admin.statistical',
				'namespace' => 'pay-in',
				'level'  => [1],
				'icon'  => 'fa-circle-o'
			],
		]
	],
];
