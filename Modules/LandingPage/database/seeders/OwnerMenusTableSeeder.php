<?php

namespace Modules\LandingPage\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\LandingPageSetting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert into 'owner_menus' table if not exists
        $ownerMenus = [
            [
                'id' => 1,
                'name' => 'Pages',
                'content' => '[[{"id":"1"},{"id":"2"},{"id":"3"}]]',
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Custom Page',
                'content' => '[[{"id":4},{"id":5},{"id":6}]]',
                'created_by' => 1,
            ]
        ];

        foreach ($ownerMenus as $menu) {
            DB::table('owner_menus')->insertOrIgnore([
                'id' => $menu['id'],
                'name' => $menu['name'],
                'content' => $menu['content'],
                'created_by' => $menu['created_by'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        // Insert into 'owner_menu_items' table if not exists
        $ownerMenuItems = [
            [
                'id' => 1,
                'menu_id' => 1,
                'title' => 'About Us',
                'slug' => 'about_us',
                'type' => 'page',
            ],
            [
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Terms and Conditions',
                'slug' => 'terms_and_conditions',
                'type' => 'page',
            ],
            [
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Privacy Policy',
                'slug' => 'privacy_policy',
                'type' => 'page',
            ],
            [
                'id' => 4,
                'menu_id' => 2,
                'title' => 'About Us',
                'slug' => 'about_us',
                'type' => 'page',
            ],
            [
                'id' => 5,
                'menu_id' => 2,
                'title' => 'Terms and Conditions',
                'slug' => 'terms_and_conditions',
                'type' => 'page',
            ],
            [
                'id' => 6,
                'menu_id' => 2,
                'title' => 'Privacy Policy',
                'slug' => 'privacy_policy',
                'type' => 'page',
            ]
        ];

        foreach ($ownerMenuItems as $item) {
            DB::table('owner_menu_items')->insertOrIgnore([
                'id' => $item['id'],
                'menu_id' => $item['menu_id'],
                'title' => $item['title'],
                'slug' => $item['slug'],
                'type' => $item['type'],
                'icon' => null,
                'target' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        // Insert into 'owner_menu_settings' table if not exists
        $ownerMenuSettings = [
            'id' => 1,
            'menus_id' => '2,1',
            'enable_header' => 'on',
            'enable_login' => 'on',
            'enable_footer' => 'on',
            'created_by' => 1,
        ];

        DB::table('owner_menu_settings')->insertOrIgnore([
            'id' => $ownerMenuSettings['id'],
            'menus_id' => $ownerMenuSettings['menus_id'],
            'enable_header' => $ownerMenuSettings['enable_header'],
            'enable_login' => $ownerMenuSettings['enable_login'],
            'enable_footer' => $ownerMenuSettings['enable_footer'],
            'created_by' => $ownerMenuSettings['created_by'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
