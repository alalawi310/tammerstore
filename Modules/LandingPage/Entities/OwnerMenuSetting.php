<?php

namespace Modules\LandingPage\Entities;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utility;
use Illuminate\Support\Facades\Storage;
use Modules\LandingPage\Entities\OwnerMenu;
use Modules\LandingPage\Entities\OwnerMenuItem;

class OwnerMenuSetting extends Model
{
    use HasFactory;


    protected $fillable = [
        'menus_id',
        'enable_header',
        'enable_login',
        'enable_footer',
        'enable_dropdown'
    ];

    public static function get_ownernav_menu($menuid) {
        // Convert comma-separated string to array if necessary
        if (!is_array($menuid)) {
            $menuid = explode(',', $menuid);
        }

        $topNavs = OwnerMenu::whereIn('id', $menuid)->get();

        if ($topNavs->isEmpty()) {
            return '';
        }

        $combinedNavItems = [];

        foreach ($topNavs as $topNav) {
            $topNavName = $topNav->name;
            $topNavItems = json_decode($topNav->content);

            if ($topNavItems) {
                $items = [];
                foreach ($topNavItems[0] as $menu) {
                    $menu->title = OwnerMenuItem::where('id', $menu->id)->value('title');
                    $menu->slug = OwnerMenuItem::where('id', $menu->id)->value('slug');
                    $menu->target = OwnerMenuItem::where('id', $menu->id)->value('target');
                    $menu->type = OwnerMenuItem::where('id', $menu->id)->value('type');

                    if (!empty($menu->children[0])) {
                        foreach ($menu->children[0] as $child) {
                            $child->title = OwnerMenuItem::where('id', $child->id)->value('title');
                            $child->slug = OwnerMenuItem::where('id', $child->id)->value('slug');
                            $child->target = OwnerMenuItem::where('id', $child->id)->value('target');
                            $child->type = OwnerMenuItem::where('id', $child->id)->value('type');
                        }
                    }
                    $items[] = $menu;
                }

                $combinedNavItems[] = [
                    'name' => $topNavName,
                    'items' => $items
                ];
            }
        }
        return $combinedNavItems;
    }

}
