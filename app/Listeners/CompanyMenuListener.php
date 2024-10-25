<?php

namespace App\Listeners;

use App\Events\CompanyMenuEvent;
use App\Models\Plan;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $setting = getAdminAllsetting();
        if (auth()->user()) {
            $plan = Plan::find(auth()->user()->plan_id);
        }

        $menu->add([
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'admin_dashboard',
            'parent' => null,
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Dashboard'
        ]);

        $menu->add([
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'dashboard',
            'parent' => 'admin_dashboard',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard',
            'module' => $module,
            'permission' => 'Manage Dashboard'
        ]);

        $menu->add([
            'title' => __('Store Analytics'),
            'icon' => '',
            'name' => 'store-analytics',
            'parent' => 'admin_dashboard',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'theme_analytic',
            'module' => $module,
            'permission' => 'Manage Store Analytics'
        ]);



        $menu->add([
            'title' => __('Theme Preview'),
            'icon' => 'rotate',
            'name' => 'themepreview',
            'parent' => null,
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'theme-preview.index',
            'module' => $module,
            'permission' => 'Manage Themes'
        ]);

        $menu->add([
            'title' => __('Store Setting'),
            'icon' => 'settings-automation',
            'name' => 'storesetting',
            'parent' => null,
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'app-setting.index',
            'module' => $module,
            'permission' => 'Manage Store Setting'
        ]);

        $menu->add([
            'title' => __('Mobile App Settings'),
            'icon' => 'settings-automation',
            'name' => 'mobilescreensetting',
            'parent' => null,
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'mobilescreen.content',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Staff'),
            'icon' => 'users',
            'name' => 'staff',
            'parent' => null,
            'order' => 80,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage User'
        ]);

        $menu->add([
            'title' => __('Roles'),
            'icon' => '',
            'name' => 'roles',
            'parent' => 'staff',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'roles.index',
            'module' => $module,
            'permission' => 'Manage Role'
        ]);

        $menu->add([
            'title' => __('User'),
            'icon' => '',
            'name' => 'user',
            'parent' => 'staff',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'users.index',
            'module' => $module,
            'permission' => 'Manage User'
        ]);

        $menu->add([
            'title' => __('Delivery Boy'),
            'icon' => 'truck',
            'name' => 'deliveryboy',
            'parent' => null,
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'deliveryboy.index',
            'module' => $module,
            'permission' => 'Manage Deliveryboy'
        ]);


        /** Products Start */
        $menu->add([
            'title' => __('Products'),
            'icon' => 'shopping-cart',
            'name' => 'products',
            'parent' => null,
            'order' => 120,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Products'
        ]);

        $menu->add([
            'title' => __('Brand'),
            'icon' => 'home',
            'name' => 'productBrand',
            'parent' => 'products',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-brand.index',
            'module' => $module,
            'permission' => 'Manage Product Brand'
        ]);

        $menu->add([
            'title' => __('Label'),
            'icon' => 'home',
            'name' => 'productLabel',
            'parent' => 'products',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-label.index',
            'module' => $module,
            'permission' => 'Manage Product Label'
        ]);

        $menu->add([
            'title' => __('Main Category'),
            'icon' => 'home',
            'name' => 'maincategory',
            'parent' => 'products',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'main-category.index',
            'module' => $module,
            'permission' => 'Manage Product Category'
        ]);

        $menu->add([
            'title' => __('Sub Category'),
            'icon' => 'home',
            'name' => 'subcategory',
            'parent' => 'products',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sub-category.index',
            'module' => $module,
            'permission' => 'Manage Product Sub Category'
        ]);

        $menu->add([
            'title' => __('Product'),
            'icon' => 'home',
            'name' => 'product',
            'parent' => 'products',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product.index',
            'module' => $module,
            'permission' => 'Manage Products'
        ]);

        $menu->add([
            'title' => __('Attributes'),
            'icon' => 'home',
            'name' => 'attributes',
            'parent' => 'products',
            'order' => 6,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-attributes.index',
            'module' => $module,
            'permission' => 'Manage Attributes'
        ]);
        if(isset($setting['taxes']) && $setting['taxes'] == 'on' && isset($plan) && $plan->enable_tax == 'on') {
            $menu->add([
                'title' => __('Tax'),
                'icon' => 'home',
                'name' => 'tax',
                'parent' => 'products',
                'order' => 7,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'taxes.index',
                'module' => $module,
                'permission' => 'Manage Tax'
            ]);
        }


        $menu->add([
            'title' => __('Testimonial'),
            'icon' => 'home',
            'name' => 'Testimonial',
            'parent' => 'products',
            'order' => 8,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'testimonial.index',
            'module' => $module,
            'permission' => 'Manage Testimonial'
        ]);

        $menu->add([
            'title' => __('Question Answer'),
            'icon' => 'home',
            'name' => 'question_answer',
            'parent' => 'products',
            'order' => 9,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-question.index',
            'module' => $module,
            'permission' => 'Manage Product Question'
        ]);
        /** Products End */

        /**shipping start */
        $menu->add([
            'title' => __('Shipping'),
            'icon' => 'truck-delivery',
            'name' => 'shipping',
            'parent' => null,
            'order' => 140,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Shipping'
        ]);
        $menu->add([
            'title' => __('Shipping Class'),
            'icon' => '',
            'name' => 'shipping class',
            'parent' => 'shipping',
            'order' => 34,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shipping.index',
            'module' => $module,
            'permission' => 'Manage Shipping Class'
        ]);
        $menu->add([
            'title' => __('Shipping Zone'),
            'icon' => '',
            'name' => 'shipping zone',
            'parent' => 'shipping',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shipping-zone.index',
            'module' => $module,
            'permission' => 'Manage Shipping Zone'
        ]);
        /** shipping end */

        /** order start */
        $menu->add([
            'title' => __('Orders'),
            'icon' => 'briefcase',
            'name' => 'orders',
            'parent' => null,
            'order' => 160,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Order'
        ]);

        $menu->add([
            'title' => __('Orders'),
            'icon' => 'user',
            'name' => 'order',
            'parent' => 'orders',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'order.index',
            'module' => $module,
            'permission' => 'Manage Order'
        ]);

        $menu->add([
            'title' => __('Order Refund Request'),
            'icon' => 'user',
            'name' => 'order-refund-request',
            'parent' => 'orders',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'refund-request.index',
            'module' => $module,
            'permission' => 'Manage Order Refund Request'
        ]);
        /** order end */

        /** customer start */
        $menu->add([
            'title' => __('Customers'),
            'icon' => 'user',
            'name' => 'customers',
            'parent' => null,
            'order' => 180,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'customer.index',
            'module' => $module,
            'permission' => 'Manage Customer'
        ]);
        /** customer end */

        /**Reports Start */
        $menu->add([
            'title' => __('Reports'),
            'icon' => 'chart-bar',
            'name' => 'reports',
            'parent' => null,
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Reports'
        ]);

        $menu->add([
            'title' => __('Customer Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'reports',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.index',
            'module' => $module,
            'permission' => 'Manage Reports'
        ]);

        $menu->add([
            'title' => __('Order Reports'),
            'icon' => 'home',
            'name' => 'order_reports',
            'parent' => 'reports',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Order Reports'
        ]);

        $menu->add([
            'title' => __('Sales Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_report',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sales Product Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_product_report',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sales Category Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_category_report',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sales Downloadable Product'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_downloadable_report',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sales Brand Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_brand_report',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Country Based Order Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 6,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.orderCountryReport',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Top Sales Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'reports',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.top_product',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Order Status Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 7,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.orderStatusReport',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Stock Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'reports',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.stock_report',
            'module' => $module,
            'permission' => 'Manage Stock Reports'
        ]);
        /**Reports End */

        /**Marketing Start */
        $menu->add([
            'title' => __('Marketing'),
            'icon' => 'confetti',
            'name' => 'marketing',
            'parent' => null,
            'order' => 220,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Marketing'
        ]);

        $menu->add([
            'title' => __('Coupon'),
            'icon' => 'home',
            'name' => 'coupon',
            'parent' => 'marketing',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'coupon.index',
            'module' => $module,
            'permission' => 'Manage Coupon'
        ]);

        $menu->add([
            'title' => __('Newsletter'),
            'icon' => 'home',
            'name' => 'newsletter',
            'parent' => 'marketing',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'newsletter.index',
            'module' => $module,
            'permission' => 'Manage Newsletter'
        ]);
        $menu->add([
            'title' => __('Flash Sale'),
            'icon' => 'home',
            'name' => 'flashsale',
            'parent' => 'marketing',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'flash-sale.index',
            'module' => $module,
            'permission' => 'Manage Flash Sale'
        ]);

        $menu->add([
            'title' => __('Wishlist'),
            'icon' => 'home',
            'name' => 'wishlist',
            'parent' => 'marketing',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wishlist.index',
            'module' => $module,
            'permission' => 'Manage Wishlist'
        ]);

        $menu->add([
            'title' => __('Abandon Cart'),
            'icon' => 'home',
            'name' => 'abandon_cart',
            'parent' => 'marketing',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'abandon.carts.handled',
            'module' => $module,
            'permission' => 'Manage Cart'
        ]);
        /**Marketing End */

        /**WooCommerce Start */
        if(isset($setting['woocommerce_setting_enabled']) && $setting['woocommerce_setting_enabled'] == 'on')
        {
            $menu->add([
                'title' => __('WooCommerce'),
                'icon' => 'letter-w',
                'name' => 'WooCommerce',
                'parent' => null,
                'order' => 240,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => 'Manage Woocommerce'
            ]);

            $menu->add([
                'title' => __('Main Category'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'WooCommerce',
                'order' => 1,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'woocom_category.index',
                'module' => $module,
                'permission' => 'Manage Woocommerce Category'
            ]);

            $menu->add([
                'title' => __('Sub Category'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'WooCommerce',
                'order' => 2,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'woocom_sub_category.index',
                'module' => $module,
                'permission' => 'Manage Woocommerce SubCategory'
            ]);

            $menu->add([
                'title' => __('Product'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'WooCommerce',
                'order' => 3,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'woocom_product.index',
                'module' => $module,
                'permission' => 'Manage Woocommerce Product'
            ]);

            $menu->add([
                'title' => __('Customer'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'WooCommerce',
                'order' => 4,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'woocom_customer.index',
                'module' => $module,
                'permission' => 'Manage Woocommerce Customer'
            ]);

            $menu->add([
                'title' => __('Coupon'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'WooCommerce',
                'order' => 5,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'woocom_coupon.index',
                'module' => $module,
                'permission' => 'Manage Woocommerce Coupon'
            ]);
        }

        /** WooCommerce End */
        /**Shopify Start */
        if(isset($setting['shopify_setting_enabled']) && $setting['shopify_setting_enabled'] == 'on')
        {
            $menu->add([
                'title' => __('Shopify'),
                'icon' => 'letter-s',
                'name' => 'shopify',
                'parent' => null,
                'order' => 260,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => 'Manage Shopify'
            ]);

            $menu->add([
                'title' => __('Main Category'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'shopify',
                'order' => 1,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'shopify_category.index',
                'module' => $module,
                'permission' => 'Manage Shopify Category'
            ]);

            $menu->add([
                'title' => __('Sub Category'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'shopify',
                'order' => 2,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'shopify_sub_category.index',
                'module' => $module,
                'permission' => 'Manage Shopify SubCategory'
            ]);

            $menu->add([
                'title' => __('Product'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'shopify',
                'order' => 3,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'shopify_product.index',
                'module' => $module,
                'permission' => 'Manage Shopify Product'
            ]);

            $menu->add([
                'title' => __('Customer'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'shopify',
                'order' => 4,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'shopify_customer.index',
                'module' => $module,
                'permission' => 'Manage Shopify Customer'
            ]);

            $menu->add([
                'title' => __('Coupon'),
                'icon' => 'home',
                'name' => '',
                'parent' => 'shopify',
                'order' => 5,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'shopify_coupon.index',
                'module' => $module,
                'permission' => 'Manage Shopify Coupon'
            ]);
        }
        /**Shopify End */

        /**Support ticket start */
        $menu->add([
            'title' => __('Support Ticket'),
            'icon' => 'ticket',
            'name' => 'support_ticket.index',
            'parent' => null,
            'order' => 280,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'support_ticket.index',
            'module' => $module,
            'permission' => 'Manage Support Ticket'
        ]);
        /**Support ticket End */
        /**POS start */
        $menu->add([
            'title' => __('POS'),
            'icon' => 'layers-difference',
            'name' => 'pos',
            'parent' => null,
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pos.index',
            'module' => $module,
            'permission' => 'Manage Pos'
        ]);
        /**POS End */
        /**CMS start */
        $menu->add([
            'title' => __('CMS'),
            'icon' => 'layout-cards',
            'name' => 'cms',
            'parent' => null,
            'order' => 320,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage CMS'
        ]);

        $menu->add([
            'title' => __('Menu'),
            'icon' => 'home',
            'name' => 'menu',
            'parent' => 'cms',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'menus.index',
            'module' => $module,
            'permission' => 'Manage Menu'
        ]);

        $menu->add([
            'title' => __('Pages'),
            'icon' => 'home',
            'name' => 'pages',
            'parent' => 'cms',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pages.index',
            'module' => $module,
            'permission' => 'Manage Page'
        ]);

        $menu->add([
            'title' => __('Blog Section'),
            'icon' => 'home',
            'name' => 'blog_section',
            'parent' => 'cms',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'Manage Blog'
        ]);

        $menu->add([
            'title' => __('Blog'),
            'icon' => '',
            'name' => 'blog',
            'parent' => 'blog_section',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'blog.index',
            'module' => $module,
            'permission' => 'Manage Blog'
        ]);

        $menu->add([
            'title' => __('Blog Category'),
            'icon' => '',
            'name' => 'blog-category',
            'parent' => 'blog_section',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'blog-category.index',
            'module' => $module,
            'permission' => 'Manage Blog Category'
        ]);

        $menu->add([
            'title' => __('Faqs'),
            'icon' => 'home',
            'name' => 'faq',
            'parent' => 'cms',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'faqs.index',
            'module' => $module,
            'permission' => 'Manage Faqs'
        ]);

        $menu->add([
            'title' => __('Tag'),
            'icon' => 'home',
            'name' => 'tag',
            'parent' => 'cms',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'tag.index',
            'module' => $module,
            'permission' => 'Manage Tag'
        ]);

        $menu->add([
            'title' => __('Contact Us'),
            'icon' => 'home',
            'name' => 'contact-us',
            'parent' => 'cms',
            'order' =>  6,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'contacts.index',
            'module' => $module,
            'permission' => 'Manage Contact Us'
        ]);
        /**CMS End */

        $menu->add([
            'title' => __('Plan'),
            'icon' => 'trophy',
            'name' => 'plan',
            'parent' => null,
            'order' => 340,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plan.index',
            'module' => $module,
            'permission' => 'Manage Plan'
        ]);

        $menu->add([
            'title' => __('Settings'),
            'icon' => 'settings',
            'name' => 'settings',
            'parent' => null,
            'order' => 360,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'setting.index',
            'module' => $module,
            'permission' => 'Manage Setting'
        ]);

    }
}
