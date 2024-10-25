<?php

namespace Modules\LandingPage\Listeners;

use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'LandingPage';
        $menu = $event->menu;

        $menu->add([
            'title' => __('CMS'),
            'icon' => 'package',
            'name' => 'landing-page',
            'parent' => null,
            'order' => 220,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => 'LandingPage',
            'permission' => 'Manage CMS'
        ]);
        $menu->add([
            'title' => __('Landing Page'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'landingpage.index',
            'module' => 'LandingPage',
            'permission' => ''
        ]);

        $modules = getshowModuleList();
        if (is_array($modules) && count($modules) > 0) {
            $menu->add([
                'title' => __('Marketplace'),
                'icon' => 'settings',
                'name' => '',
                'parent' => 'landing-page',
                'order' => 2,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'marketplace.index',
                'module' => 'LandingPage',
                'permission' => ''
            ]);
        }

        $menu->add([
            'title' => __('Menus'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ownermenus.index',
            'module' => 'LandingPage',
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Custom Page'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'menu-pages.index',
            'module' => 'LandingPage',
            'permission' => ''
        ]);
    }
}
