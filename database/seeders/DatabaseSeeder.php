<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        \App\Models\User::truncate();
        \App\Models\User::create([
            'username' => 'betlink',
            'email' => 'betlink@gmail.com',
            'password' => 'Password@333',
            'name' => 'Link Admin'
        ]);

        $catArr = [
            ['name' => 'Common', 'description' => 'Common', 'script_id' => hexdec(uniqid()).time()],
        ];
        $catIds = [];
        \App\Models\LinkDirectory::truncate();
        foreach ($catArr as $c) {

            $cat = \App\Models\LinkDirectory::create($c);
            $catIds[] = $cat->id;
        }

        $links = [
            [
                'link' => 'https://nhacaitop.org/',
                'image' => 'https://nhacaitop.org/wp-content/uploads/2020/05/fi881.com-0720-120x600-1.gif',
                'slug' => hexdec(uniqid()),
                'description' => 'Link 1',
                'position' => 'middle_left'

            ],
            [
                'link' => 'https://nhacaitop.org/',
                'image' => 'https://nhacaitop.org/wp-content/uploads/2020/05/crown-banner-topright-20210612.gif',
                'slug' => hexdec(uniqid()),
                'description' => 'Link 2',
                'position' => 'middle_right'

            ],
            [
                'link' => 'https://nhacaitop.org/',
                'image' => 'https://nhacaitop.org/wp-content/uploads/2020/05/fi881.com-0720-728x90-1.gif',
                'slug' => hexdec(uniqid()),
                'description' => 'Link 3',
                'position' => 'bottom_left'
            ],
            [
                'link' => 'https://nhacaitop.org/',
                'image' => 'https://nhacaitop.org/wp-content/uploads/2020/05/crown-banner-bottomright-20210612.gif',
                'slug' => hexdec(uniqid()),
                'description' => 'Link 3',
                'position' => 'bottom_right'
            ]
        ];
        \App\Models\Link::truncate();
        foreach ($links as $link) {
            $key = array_rand($catIds);
            $link['link_directory_id'] = $catIds[$key];
            \App\Models\Link::create($link);
        }
    }
}
