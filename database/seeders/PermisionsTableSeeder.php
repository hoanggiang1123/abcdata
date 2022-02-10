<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permisions = [
            [
                'name' => 'Banners',
                'description' => 'Danh sách quyền banner',
                'permision_array' => [
                    [
                        'name' => 'Danh sách banner',
                        'description' => 'Danh sách banner',
                        'controller' => 'LinkController@index'
                    ],
                    [
                        'name' => 'Xem chi tiết banner',
                        'description' => 'Xem Chi Tiết Banner',
                        'controller' => 'LinkController@show'
                    ],
                    [
                        'name' => 'Chỉnh sửa Banner',
                        'description' => 'Chỉnh sửa Banner',
                        'controller' => 'LinkController@update'
                    ],
                    [
                        'name' => 'Upload Banner',
                        'description' => 'Upload Banner',
                        'controller' => 'LinkController@upload'
                    ],
                    [
                        'name' => 'Tạo Banner',
                        'description' => 'Tạo Banner',
                        'controller' => 'LinkController@store'
                    ],
                    [
                        'name' => 'Clone Banner',
                        'description' => 'Clone Banner',
                        'controller' => 'LinkController@createMany'
                    ],
                    [
                        'name' => 'Danh sách link clicked',
                        'description' => 'Danh sách link clicked',
                        'controller' => 'LinkHitController@index'
                    ],
                    [
                        'name' => 'Chi tiết link clicked',
                        'description' => 'Chi tiết link clicked',
                        'controller' => 'LinkHitController@getLinkHitByLinkId'
                    ],
                    [
                        'name' => 'Chart link clicked',
                        'description' => 'Chart link clicked',
                        'controller' => 'LinkHitController@getChartClick'
                    ],
                ]
            ],
            [
                'name' => 'Category',
                'description' => 'Danh sách quyền category',
                'permision_array' => [
                    [
                        'name' => 'Danh sách category',
                        'description' => 'Danh sách category',
                        'controller' => 'LinkDirectoryController@index'
                    ],
                    [
                        'name' => 'Tạo category',
                        'description' => 'Tạo category',
                        'controller' => 'LinkDirectoryController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa category',
                        'description' => 'Chỉnh sửa category',
                        'controller' => 'LinkDirectoryController@update'
                    ],
                ]
            ],
            [
                'name' => 'Media',
                'description' => 'Danh sách quyền Media',
                'permision_array' => [
                    [
                        'name' => 'Danh sách media',
                        'description' => 'Danh sách media',
                        'controller' => 'MediaController@index'
                    ],
                    [
                        'name' => 'Tạo media',
                        'description' => 'Tạo media',
                        'controller' => 'MediaController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa media',
                        'description' => 'Chỉnh sửa media',
                        'controller' => 'MediaController@update'
                    ],
                    [
                        'name' => 'Xóa media',
                        'description' => 'Xóa media',
                        'controller' => 'MediaController@destroy'
                    ],
                ]
            ],
            [
                'name' => 'Danh sách quyền Link Module',
                'description' => 'Danh sách quyền Link Module',
                'permision_array' => [
                    [
                        'name' => 'Danh sách link',
                        'description' => 'Danh sách link',
                        'controller' => 'EnterLinkController@index'
                    ],
                    [
                        'name' => 'Tạo link',
                        'description' => 'Tạo link',
                        'controller' => 'EnterLinkController@store'
                    ],
                    [
                        'name' => 'Clone link',
                        'description' => 'Clone link',
                        'controller' => 'EnterLinkController@createMany'
                    ],
                    [
                        'name' => 'Chỉnh sửa link',
                        'description' => 'Chỉnh sửa link',
                        'controller' => 'EnterLinkController@update'
                    ],
                    [
                        'name' => 'Chi tiết link',
                        'description' => 'Chi tiết link',
                        'controller' => 'EnterLinkController@show'
                    ],
                    [
                        'name' => 'Xóa nhiều link',
                        'description' => 'Xóa nhiều link',
                        'controller' => 'EnterLinkController@deleteMany'
                    ],
                    [
                        'name' => 'Xóa link',
                        'description' => 'Xóa link',
                        'controller' => 'EnterLinkController@destroy'
                    ]
                ]
            ],
            [
                'name' => 'Danh sách quyền Folder Module',
                'description' => 'Danh sách quyền Folder Module',
                'permision_array' => [
                    [
                        'name' => 'Danh sách folder',
                        'description' => 'Danh sách link',
                        'controller' => 'FolderController@index'
                    ],
                    [
                        'name' => 'Tạo folder',
                        'description' => 'Tạo folder',
                        'controller' => 'FolderController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa folder',
                        'description' => 'Chỉnh sửa folder',
                        'controller' => 'FolderController@update'
                    ],
                    [
                        'name' => 'Chi tiết link',
                        'description' => 'Chi tiết link',
                        'controller' => 'EnterLinkController@show'
                    ],
                    [
                        'name' => 'Chỉnh sửa vote',
                        'description' => 'Chỉnh sửa vote',
                        'controller' => 'EnterLinkController@editVote'
                    ],
                    [
                        'name' => 'Xóa Folder',
                        'description' => 'Xóa Folder',
                        'controller' => 'FolderController@deletes'
                    ],
                ]
            ],
            [
                'name' => 'Danh sách quyền Role & Permision',
                'description' => 'Danh sách quyền Role & Permision',
                'permision_array' => [
                    [
                        'name' => 'Danh sách roles',
                        'description' => 'Danh sách roles',
                        'controller' => 'RoleController@index'
                    ],
                    [
                        'name' => 'Danh sách Permisions',
                        'description' => 'Danh sách Permisions',
                        'controller' => 'PermisionController@index'
                    ],
                    [
                        'name' => 'Tạo roles',
                        'description' => 'Tạo roles',
                        'controller' => 'RoleController@create'
                    ],
                    [
                        'name' => 'Chỉnh sửa roles',
                        'description' => 'Chi tiết link',
                        'controller' => 'RoleController@update'
                    ]
                ]
            ],
            [
                'name' => 'Danh sách quyền User',
                'description' => 'Danh sách quyền User',
                'permision_array' => [
                    [
                        'name' => 'Danh sách users',
                        'description' => 'Danh sách users',
                        'controller' => 'UserController@index'
                    ],
                    [
                        'name' => 'Tạo users',
                        'description' => 'Tạo users',
                        'controller' => 'UserController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa users',
                        'description' => 'Chỉnh sửa users',
                        'controller' => 'UserController@update'
                    ]
                ]
            ],
            [
                'name' => 'Danh sách quyền Plugin Repo',
                'description' => 'Danh sách quyền Plugin Repo',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list plugin repo',
                        'description' => 'Danh sách list plugin repo',
                        'controller' => 'PluginRepoController@index'
                    ],
                    [
                        'name' => 'Tạo plugin repo',
                        'description' => 'Tạo plugin repo',
                        'controller' => 'PluginRepoController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa plugin repo',
                        'description' => 'Chỉnh sửa plugin repo',
                        'controller' => 'PluginRepoController@update'
                    ],
                    [
                        'name' => 'Upload plugin file',
                        'description' => 'Upload plugin file',
                        'controller' => 'PluginRepoController@uploadPlugin'
                    ]
                ]
            ],
            [
                'name' => 'Danh sách quyền TeleSale',
                'description' => 'Danh sách quyền TeleSale',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list TeleSale',
                        'description' => 'Danh sách list TeleSale',
                        'controller' => 'TeleSaleController@index'
                    ],
                    [
                        'name' => 'Tạo TeleSale',
                        'description' => 'Tạo TeleSale',
                        'controller' => 'TeleSaleController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa TeleSale',
                        'description' => 'Chỉnh sửa TeleSale',
                        'controller' => 'TeleSaleController@update'
                    ],
                    [
                        'name' => 'Xóa TeleSale',
                        'description' => 'Xóa TeleSale',
                        'controller' => 'TeleSaleController@deleteMany'
                    ],
                    [
                        'name' => 'Assign TeleSale',
                        'description' => 'Assign TeleSale',
                        'controller' => 'TeleSaleController@updateAssign'
                    ],
                    [
                        'name' => 'Check Duplicate TeleSale',
                        'description' => 'Check Duplicate TeleSale',
                        'controller' => 'TeleSaleController@checkDuplicates'
                    ],
                    [
                        'name' => 'Import Data TeleSale',
                        'description' => 'Import Data TeleSale',
                        'controller' => 'TeleSaleController@import'
                    ],

                ]
            ],
            [
                'name' => 'Danh sách quyền TeleSale Line',
                'description' => 'Danh sách quyền TeleSale Line',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list TeleSale Line',
                        'description' => 'Danh sách list TeleSale Line',
                        'controller' => 'TeleSaleLineController@index'
                    ],
                    [
                        'name' => 'Tạo TeleSale Line',
                        'description' => 'Tạo TeleSale Line',
                        'controller' => 'TeleSaleLineController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa TeleSale Line',
                        'description' => 'Chỉnh sửa TeleSale Line',
                        'controller' => 'TeleSaleLineController@update'
                    ],
                    [
                        'name' => 'Xóa TeleSale Line',
                        'description' => 'Xóa TeleSale Line',
                        'controller' => 'TeleSaleLineController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Post',
                'description' => 'Danh sách quyền Post',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Post',
                        'description' => 'Danh sách list Post',
                        'controller' => 'PostController@index'
                    ],
                    [
                        'name' => 'Tạo Post',
                        'description' => 'Tạo Post',
                        'controller' => 'PostController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Post',
                        'description' => 'Chỉnh sửa Post',
                        'controller' => 'PostController@update'
                    ],
                    [
                        'name' => 'Xóa Post',
                        'description' => 'Xóa Post',
                        'controller' => 'PostController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Keywords',
                'description' => 'Danh sách quyền Keywords',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Keywords',
                        'description' => 'Danh sách list Keywords',
                        'controller' => 'KeywordController@index'
                    ],
                    [
                        'name' => 'Tạo Keywords',
                        'description' => 'Tạo Keywords',
                        'controller' => 'KeywordController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Keywords',
                        'description' => 'Chỉnh sửa Keywords',
                        'controller' => 'KeywordController@update'
                    ],
                    [
                        'name' => 'Xóa Keywords',
                        'description' => 'Xóa Keywords',
                        'controller' => 'KeywordController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Satilite Domain',
                'description' => 'Danh sách quyền Satilite Domain',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Satilite Domain',
                        'description' => 'Danh sách list Satilite Domain',
                        'controller' => 'SatiliteDomainController@index'
                    ],
                    [
                        'name' => 'Tạo Satilite Domain',
                        'description' => 'Tạo Satilite Domain',
                        'controller' => 'SatiliteDomainController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Satilite Domain',
                        'description' => 'Chỉnh sửa Satilite Domain',
                        'controller' => 'SatiliteDomainController@update'
                    ],
                    [
                        'name' => 'Xóa Satilite Domain',
                        'description' => 'Xóa Satilite Domain',
                        'controller' => 'SatiliteDomainController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Keyword Tracking',
                'description' => 'Danh sách quyền Keyword Tracking',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Keyword Tracking',
                        'description' => 'Danh sách list Keyword Tracking',
                        'controller' => 'KeywordTrackingController@index'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền TeleSale Agent',
                'description' => 'Danh sách quyền TeleSale Agent',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list TeleSale Agent',
                        'description' => 'Danh sách list TeleSale Agent',
                        'controller' => 'TeleSaleAgentController@index'
                    ],
                    [
                        'name' => 'Tạo TeleSale Agent',
                        'description' => 'Tạo TeleSale Agent',
                        'controller' => 'TeleSaleAgentController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa TeleSale Agent',
                        'description' => 'Chỉnh sửa TeleSale Agent',
                        'controller' => 'TeleSaleAgentController@update'
                    ],
                    [
                        'name' => 'Xóa TeleSale Agent',
                        'description' => 'Xóa TeleSale Agent',
                        'controller' => 'TeleSaleAgentController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Fb Leagues',
                'description' => 'Danh sách quyền Fb Leagues',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Fb Leagues',
                        'description' => 'Danh sách list Fb Leagues',
                        'controller' => 'FbLeagueController@index'
                    ],
                    [
                        'name' => 'Tạo Fb Leagues',
                        'description' => 'Tạo Fb Leagues',
                        'controller' => 'FbLeagueController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Fb Leagues',
                        'description' => 'Chỉnh sửa Fb Leagues',
                        'controller' => 'FbLeagueController@update'
                    ],
                    [
                        'name' => 'Xóa Fb Leagues',
                        'description' => 'Xóa Fb Leagues',
                        'controller' => 'FbLeagueController@destroy'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Data Vip Level',
                'description' => 'Danh sách quyền Data Vip Level',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Data Vip Levels',
                        'description' => 'Danh sách list Data Vip Levels',
                        'controller' => 'TeleSaleVipController@index'
                    ],
                    [
                        'name' => 'Tạo Data Vip Levels',
                        'description' => 'Tạo Data Vip Levels',
                        'controller' => 'TeleSaleVipController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Data Vip Levels',
                        'description' => 'Chỉnh sửa Data Vip Levels',
                        'controller' => 'TeleSaleVipController@update'
                    ],
                    [
                        'name' => 'Xóa Data Vip Levels',
                        'description' => 'Xóa Data Vip Levels',
                        'controller' => 'TeleSaleVipController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách Data Email Permision',
                'description' => 'Danh sách Data Email Permision',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Data Email Permision',
                        'description' => 'Danh sách list Data Email Permision',
                        'controller' => 'DataEmailController@index'
                    ],

                ]
            ],
            [
                'name' => 'Danh sách quyền Data Category',
                'description' => 'Danh sách quyền Data Category',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Data Category',
                        'description' => 'Danh sách list Data Category',
                        'controller' => 'TeleSaleCategoryController@index'
                    ],
                    [
                        'name' => 'Tạo Data Category',
                        'description' => 'Tạo Data Category',
                        'controller' => 'TeleSaleCategoryController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Data Category',
                        'description' => 'Chỉnh sửa Data Categorys',
                        'controller' => 'TeleSaleCategoryController@update'
                    ],
                    [
                        'name' => 'Xóa Data Category',
                        'description' => 'Xóa Data Category',
                        'controller' => 'TeleSaleCategoryController@deleteMany'
                    ]

                ]
            ],
            [
                'name' => 'Danh sách quyền Popup',
                'description' => 'Danh sách quyền Popup',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Popup',
                        'description' => 'Danh sách list Popup',
                        'controller' => 'PopupController@index'
                    ],
                    [
                        'name' => 'Tạo Popup',
                        'description' => 'Tạo Popup',
                        'controller' => 'PopupController@store'
                    ],
                    [
                        'name' => 'Chỉnh sửa Popup',
                        'description' => 'Chỉnh sửa Popup',
                        'controller' => 'PopupController@update'
                    ],
                    [
                        'name' => 'Xóa Popup',
                        'description' => 'Xóa Popup',
                        'controller' => 'PopupController@deleteMany'
                    ]
                ]
            ],
            [
                'name' => 'Danh sách quyền Data Record',
                'description' => 'Danh sách quyền Data Record',
                'permision_array' => [
                    [
                        'name' => 'Danh sách list Data Record',
                        'description' => 'Danh sách list Data Record',
                        'controller' => 'TeleSaleRecordController@index'
                    ],
                    [
                        'name' => 'Chỉnh sửa Data Record',
                        'description' => 'Chỉnh sửa Data Record',
                        'controller' => 'TeleSaleRecordController@update'
                    ],
                    [
                        'name' => 'Xóa Data Record',
                        'description' => 'Xóa Data Record',
                        'controller' => 'TeleSaleRecordController@deleteMany'
                    ],
                    [
                        'name' => 'Thống kê Data Statistic',
                        'description' => 'Thống kê Data Statistic',
                        'controller' => 'TeleSaleRecordController@statisticCall'
                    ]
                ]
            ],
        ];
        \App\Models\Permision::truncate();
        foreach ($permisions as $pers) {
            $permision = \App\Models\Permision::create([
                'name' => $pers['name'],
                'description' => $pers['description'],
                'parent_id' => 0
            ]);
            foreach($pers['permision_array'] as $p) {
                \App\Models\Permision::create([
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'controller' => $p['controller'],
                    'parent_id' => $permision->id
                ]);
            }
        }
    }
}
