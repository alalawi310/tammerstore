<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\{Customer, Country, Order, PlanOrder, Plan, PlanCoupon, PlanRequest, Store, Setting, User,OrderBillingDetail , PixelFields, Page,Cart};
use App\Models\Faq;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\{FlashSale, ProductQuestion, Testimonial, Wishlist,TaxOption};
use Qirolab\Theme\Theme;
use App\Http\Controllers\Api\ApiController;
use Shetabit\Visitor\VisitorFacade as Visitor;
use Nwidart\Modules\Facades\Module;
use App\Models\AddOnManager;
use App\Models\ProductBrand;
use App\Http\Controllers\OfertemagController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct(Request $request)
    {

        if (!file_exists(storage_path().'/installed')) {
            header('location:install');
            exit;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function Landing()
    {
        $uri = url()->full();
        $segments = explode('/', str_replace(''.url('').'', '', $uri));
        $segments = $segments[1] ?? null;
        $local = parse_url(config('app.url'))['host'];
        // Get the request host
        $remote = request()->getHost();
        // Get the remote domain
        // remove WWW
        $remote = str_replace('www.', '', $remote);
        $subdomain = Setting::where('name', 'subdomain')->where('value', $remote)->first();
        $domain = Setting::where('name', 'domains')->where('value', $remote)->first();

        $enable_subdomain = '';
        $enable_domain = '';

        if ($subdomain || $domain) {
            if ($subdomain) {
                $enable_subdomain = Setting::where('name', 'enable_subdomain')->where('value', 'on')->where('store_id', $subdomain->store_id)->first();

            }

            if ($domain) {
                $enable_domain = Setting::where('name', 'enable_domain')->where('value', 'on')->where('store_id', $domain->store_id)->first();
            }
        }
        if ($enable_domain || $enable_subdomain) {

            if ($subdomain) {
                $enable_subdomain = Setting::where('name', 'enable_subdomain')->where('value', 'on')->where('store_id', $subdomain->store_id)->first();
                if ($enable_subdomain) {
                    $admin = User::find($enable_subdomain->created_by);

                    if ($enable_subdomain->value == 'on' && $enable_subdomain->store_id == $admin->current_store) {
                        $store = Store::find($admin->current_store);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } elseif ($enable_subdomain->value == 'on' && $enable_subdomain->store_id != $admin->current_store) {
                        $store = Store::find($enable_subdomain->store_id);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } else {
                        return $this->storeSlug($segments);
                    }
                }
            }

            if ($domain) {
                $enable_domain = Setting::where('name', 'enable_domain')->where('value', 'on')->where('store_id', $domain->store_id)->first();

                if ($enable_domain) {
                    $admin = User::find($enable_domain->created_by);

                    if ($enable_domain->value == 'on' && $enable_domain->store_id == $admin->current_store) {
                        $store = Store::find($admin->current_store);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } elseif ($enable_domain->value == 'on' && $enable_domain->store_id != $admin->current_store) {
                        $store = Store::find($enable_domain->store_id);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } else {
                        return $this->storeSlug($segments);
                    }
                }
            }
        } else {
            $settings = getSuperAdminAllSetting();
            if (isset($settings['display_landing']) && $settings['display_landing'] == 'on') {
                Artisan::call('module:migrate '. 'LandingPage');
                Artisan::call('module:seed '. 'LandingPage');
                return view('landingpage::layouts.landingpage');
            } else {
                return redirect('login');
            }
        }
    }

    public function index()
    {
        $user = auth()->user();
        Utility::defaultEmail();
        Utility::userDefaultData($user->id);

        if ($user->type == 'super admin') {
          $data = $this->handleSuperAdmin($user);

            return view('superadmin.dashboard', $data);
        } else {
            $data = $this->handleRegularUser($user);
            return view('dashboard', $data);
        }
    }

    private function handleSuperAdmin($user)
    {
        $user['total_user'] = $user->countCompany();
        $user['total_orders'] = PlanOrder::total_orders();
        $user['total_plan'] = Plan::total_plan();
        $chartData = $this->getOrderChart(['duration' => 'week']);
        $topAdmins = $user->createdAdmins()
            ->with('stores')
            ->withCount('stores')
            ->orderBy('stores_count', 'desc')
            ->limit(5)
            ->get();

        $visitors = DB::table('shetabit_visits')->whereNotNull('store_id')->pluck('store_id')->toArray();
        $visitors = array_count_values($visitors);
        arsort($visitors);
        $visitors = array_slice($visitors, 0, 5, true);

        $plan_order = Plan::most_purchese_plan();
        $coupons = PlanCoupon::get();
        $maxValue = 0;
        $couponName = '';
        foreach ($coupons as $coupon) {
            $max = $coupon->used_coupon();
            if ($max > $maxValue) {
                $maxValue = $max;
                $couponName = $coupon->name;
            }
        }

        $allStores = Order::select('store_id', DB::raw('SUM(final_price) as total_amount'))
            ->groupBy('store_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();
        $plan_requests = PlanRequest::count();

        $data =  compact('user', 'chartData', 'couponName', 'plan_order', 'plan_requests', 'allStores', 'topAdmins', 'visitors');
        return $data;
    }

    private function handleRegularUser($user)
    {
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
        $productQuery = Product::where('theme_id', APP_THEME())->where('product_type', null)->where('store_id', getCurrentStore());
        $orderQuery = Order::where('theme_id', APP_THEME())->where('store_id', getCurrentStore());

        $totalproduct = (clone $productQuery)->count();
        $today_product = (clone $productQuery)->whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $productPer = $this->calculatePercentageToday($today_product, $totalproduct);

        $totle_order = (clone $orderQuery)->count();
        $customerQuery = Customer::where('theme_id', APP_THEME())->where('store_id', getCurrentStore());
        $totle_customers = (clone $customerQuery)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->count();
        $today_customers = (clone $customerQuery)->whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $customerPer = $this->calculatePercentageToday($today_customers, $totle_customers);

        $totle_cancel_order = (clone $orderQuery)->where('delivered_status', 2)->count();

        $total_revenues = (clone $orderQuery)->where(function ($query) {
            $query->where(function ($subquery) {
                $subquery->where('delivered_status', '!=', 2)
                    ->where('delivered_status', '!=', 3);
            })->orWhere('return_status', '!=', 2);
        })->sum('final_price');

        $topSellingProductIds = (clone $orderQuery)->pluck('product_id')
            ->flatMap(function ($productIds) {
                return explode(',', $productIds);
            })
            ->map(function ($productId) {
                return (int)$productId;
            })
            ->groupBy(function ($productId) {
                return $productId;
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5)
            ->keys();

        $topSellingProducts = (clone $productQuery)->whereIn('id', $topSellingProductIds)->get();
        $theme_name = APP_THEME() ? APP_THEME() : env('DATA_INSERT_APP_THEME');
        $out_of_stock_threshold = Utility::GetValueByName('out_of_stock_threshold', $theme_name, getCurrentStore());
        $latests = (clone $productQuery)->orderBy('created_at', 'Desc')->limit(5)->get();

        $orderCountsToday = $this->getOrderCounts($orderQuery, $todayStart, $todayEnd);
        $orderCounts = $this->getOrderCounts($orderQuery);
        //$orderCountsYesterday = $this->getOrderCounts($orderQuery, $yesterdayStart, $yesterdayEnd);

        $totalOrderPer = $this->calculatePercentageToday($orderCountsToday['total'], $orderCounts['total']);
        $pendingOrderPer = $this->calculatePercentageToday($orderCountsToday['pending'], $orderCounts['pending']);
        $completeOrderPer = $this->calculatePercentageToday($orderCountsToday['complete'], $orderCounts['complete']);
        $deliveredOrderPer = $this->calculatePercentageToday($orderCountsToday['delivered'], $orderCounts['delivered']);
        $cancelOrderPer = $this->calculatePercentageToday($orderCountsToday['cancel'], $orderCounts['cancel']);
        $returnOrderPer = $this->calculatePercentageToday($orderCountsToday['return'], $orderCounts['return']);
        $shippedOrderPer = $this->calculatePercentageToday($orderCountsToday['shipped'], $orderCounts['shipped']);

        $pending_order = $orderCounts['pending'];
        $delivered_order = $orderCounts['delivered'];
        $cancel_order = $orderCounts['cancel'];
        $return_order = $orderCounts['return'];
        $confirmed_order = $orderCounts['complete'];
        $shipped_order = $orderCounts['shipped'];
        $new_orders = $orderQuery->orderBy('id', 'DESC')->limit(5)->get();
        $chartData = $this->getOrderChart(['duration' => 'week']);

        $store = Store::where('id', getCurrentStore())->first();
        $slug = $store->slug;
        $storage_limit = 0;
        $users = User::find($user->id);
        $plan = null;
        if ($users) {
            $plan = Plan::find($users->plan_id);
            if ($plan && $plan->storage_limit > 0) {
                $storage_limit = ($user->storage_limit / $plan->storage_limit) * 100;
            }
        }


        $theme_url = $this->getThemeUrl($store);

        $data = compact(
            'totalproduct', 'totle_order', 'totle_customers', 'latests', 'new_orders', 'chartData',
            'theme_url', 'store', 'storage_limit', 'users', 'plan', 'topSellingProducts', 'total_revenues',
            'totle_cancel_order', 'out_of_stock_threshold', 'theme_name', 'pending_order',
            'delivered_order', 'cancel_order', 'return_order', 'confirmed_order','totalOrderPer','pendingOrderPer','completeOrderPer','deliveredOrderPer','cancelOrderPer','returnOrderPer', 'customerPer','productPer','shippedOrderPer','shipped_order'
        );
        return $data;
    }

    private function getOrderCounts($orderQuery, $start=null, $end=null)
    {
        if (!empty($start) && !empty($end)) {
            return [
                'total' => (clone $orderQuery)->whereBetween('created_at', [$start, $end])->count(),
                'pending' => (clone $orderQuery)->where('delivered_status', 0)->whereBetween('created_at', [$start, $end])->count(),
                'delivered' => (clone $orderQuery)->where('delivered_status', 1)->whereBetween('created_at', [$start, $end])->count(),
                'complete' => (clone $orderQuery)->where('delivered_status', 4)->whereBetween('created_at', [$start, $end])->count(),
                'cancel' => (clone $orderQuery)->where('delivered_status', 2)->whereBetween('created_at', [$start, $end])->count(),
                'return' => (clone $orderQuery)->where('delivered_status', 3)->whereBetween('created_at', [$start, $end])->count(),
                'shipped' => (clone $orderQuery)->where('delivered_status', 5)->whereBetween('created_at', [$start, $end])->count(),
            ];
        } else {
            return [
                'total' => (clone $orderQuery)->count(),
                'pending' => (clone $orderQuery)->where('delivered_status', 0)->count(),
                'delivered' => (clone $orderQuery)->where('delivered_status', 1)->count(),
                'complete' => (clone $orderQuery)->where('delivered_status', 4)->count(),
                'cancel' => (clone $orderQuery)->where('delivered_status', 2)->count(),
                'return' => (clone $orderQuery)->where('delivered_status', 3)->count(),
                'shipped' => (clone $orderQuery)->where('delivered_status', 5)->count(),
            ];
        }
    }

    private function calculatePercentageToday($todayCount, $allCount)
    {
        if ($allCount == 0) {
            return $todayCount > 0 ? 100 : 0;
        }
        $percentage = (($todayCount - $allCount) / $allCount) * 100;
        if ($percentage > 0) {
            return '+ '.number_format( $percentage, 2);
        } else {
            return number_format( $percentage, 2);
        }

    }

    private function getThemeUrl($store)
    {
        $enable_storelink = Utility::GetValueByName('enable_storelink', $store->theme_id, $store->id);
        $enable_domain = Utility::GetValueByName('enable_domain', $store->theme_id, $store->id);
        $enable_subdomain = Utility::GetValueByName('enable_subdomain', $store->theme_id, $store->id);
        $domains = Utility::GetValueByName('domains', $store->theme_id, $store->id);

        if ($enable_domain == 'on') {
            return 'https://' . $domains;
        } elseif ($enable_subdomain == 'on') {
            return 'https://' . $domains;
        } elseif ($enable_storelink) {
            return route('landing_page', $store->slug);
        } else {
            return route('landing_page', $store->slug);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOrderChart($arrParam)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('message', 'You have been logged out.');
        }
        $store = Store::where('id', $user->current_store)->first();

        if (!$store) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect()->route('login')->with('message', 'You have been logged out.');
        }
        // $userstore = $this->APP_THEME;

        $userstore = $store->theme_id ?? '';
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime('-1 week +1 day');

                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week).' +1 day');
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        $registerTotal = '';
        $newguestTotal = '';
        foreach ($arrDuration as $date => $label) {
            if (auth()->user()->type == 'admin') {
                $data = Order::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->whereDate('created_at', '=', $date)
                    ->first();

                $registerTotal = Customer::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->where('regiester_date', '!=', null)
                    ->whereDate('regiester_date', '=', $date)
                    ->first();

                $newguestTotal = Customer::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->where('regiester_date', '=', null)
                    ->whereDate('last_active', '=', $date)
                    ->first();
            } else {
                $data = PlanOrder::select(\DB::raw('count(*) as total'))
                    ->whereDate('created_at', '=', $date)
                    ->first();
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data ? $data->total : 0; // Check if $data is not null

            if (auth()->user()->isAbleTo('Manage Dashboard')) {
                $arrTask['registerTotal'][] = $registerTotal ? $registerTotal->total : 0; // Check if $registerTotal is not null
                $arrTask['newguestTotal'][] = $newguestTotal ? $newguestTotal->total : 0; // Check if $newguestTotal is not null
            }
        }

        return $arrTask;
    }

    public function landing_page($storeSlug)
    {
        $uri = url()->full();
        $segments = explode('/', str_replace(''.url('').'', '', $uri));
        $segments = $segments[1] ?? null;

        $local = parse_url(config('app.url'))['host'];
        // Get the request host
        $remote = request()->getHost();
        // Get the remote domain
        // remove WWW
        $remote = str_replace('www.', '', $remote);
        $subdomain = Setting::where('name', 'subdomain')->where('value', $remote)->first();
        $domain = Setting::where('name', 'domains')->where('value', $remote)->first();

        $enable_subdomain = '';
        $enable_domain = '';

        if ($subdomain || $domain) {
            if ($subdomain) {
                $enable_subdomain = Setting::where('name', 'enable_subdomain')->where('value', 'on')->where('store_id', $subdomain->store_id)->first();
            }

            if ($domain) {
                $enable_domain = Setting::where('name', 'enable_domain')->where('value', 'on')->where('store_id', $domain->store_id)->first();
            }
        }
        if ($enable_domain || $enable_subdomain) {

            if ($subdomain) {
                $enable_subdomain = Setting::where('name', 'enable_subdomain')->where('value', 'on')->where('store_id', $subdomain->store_id)->first();
                if ($enable_subdomain) {
                    $admin = User::find($enable_subdomain->created_by);
                    if ($enable_subdomain->value == 'on' && $enable_subdomain->store_id == $admin->current_store) {
                        $store = Store::find($admin->current_store);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } else {
                        return $this->storeSlug($segments);
                    }
                }
            }

            if ($domain) {
                $enable_domain = Setting::where('name', 'enable_domain')->where('value', 'on')->where('store_id', $domain->store_id)->first();
                if ($enable_domain) {
                    $admin = User::find($enable_domain->created_by);
                    if ($enable_domain->value == 'on' && $enable_domain->store_id == $admin->current_store) {
                        $store = Store::find($admin->current_store);
                        if ($store) {
                            return $this->storeSlug($store->slug);
                        }
                    } else {
                        return $this->storeSlug($segments);
                    }
                }
            }
        } else {
            return $this->storeSlug($segments);
        }

    }

    private function storeSlug($storeSlug)
    {
        $theme_id = GetCurrenctTheme($storeSlug);
        Theme::set($theme_id);
        $data = getThemeSections($theme_id, $storeSlug, true, true);
        visitor()->visit($data['store']);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);

        if (!view()->exists('main_file', $data + $sqlData)) {
            return redirect()->back()->with('error',"Store Not Found.");
        }
        return view('main_file', $data + $sqlData);
    }

    public function faqs_page(Request $request, $storeSlug)
    {
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        $slug = $store->slug;
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $faqs = Faq::where('theme_id', $currentTheme)->where('store_id', $store->id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $section = (object) $data['section'];
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);
        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'faq_page'));

        return view('front_end.sections.pages.faq_page_section', compact('faqs', 'currentTheme', 'currantLang', 'store', 'section', 'topNavItems', 'page_json') + $data + $sqlData);
    }

    public function blog_page(Request $request, $storeSlug)
    {
        \Log::info('Store Slug: '.$storeSlug);
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $data = getThemeSections($currentTheme, $storeSlug, true, true);

        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $BlogCategory = BlogCategory::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name', 'id');
        $BlogCategory->prepend('All', '0');

        $blogs = Blog::where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'blog_page'));

        return view('front_end.sections.pages.blog_page_section', compact('BlogCategory', 'currentTheme', 'currantLang', 'store', 'section', 'topNavItems', 'blogs', 'page_json') + $data + $sqlData);
    }

    public function article_page(Request $request, $storeSlug, $id)
    {
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $blogs = Blog::where('id', $id)->where('store_id', $store_id)->get();
        $home_blogs = Blog::where('store_id', $store_id)->get();
        if ($blogs->isEmpty()) {
            abort(404);
        }

        $datas = Blog::where('theme_id', $currentTheme)->where('store_id', $store_id)->inRandomOrder()
            ->limit(3)
            ->get();

        $l_articles = Blog::where('theme_id', $currentTheme)->where('store_id', $store_id)->inRandomOrder()
            ->limit(5)
            ->get();

        $BlogCategory = BlogCategory::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name', 'id');
        $BlogCategory->prepend('All Products', '0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('product_type', null)->where('store_id', $store_id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $blog1 = Blog::where('theme_id', $currentTheme)->where('store_id', $store_id)->get();

        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'article_page'));

        return view('front_end.sections.pages.article', compact('currantLang', 'currentTheme', 'blogs', 'datas', 'l_articles', 'BlogCategory', 'homeproducts', 'blog1', 'section', 'topNavItems', 'home_blogs', 'page_json') + $data + $sqlData);

    }

    public function product_page(Request $request, $storeSlug, $categorySlug = null)
    {
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }

        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        $category_ids = [];
        $brand_ids = [];
        if ($categorySlug) {
            $category_ids = MainCategory::where('name', 'like', $categorySlug)->where('theme_id', $currentTheme)->where('store_id',$store_id)->pluck('id')->toArray();
            if(!$category_ids)
            {
                $brand_ids = ProductBrand::where('slug', 'like', $categorySlug)->where('theme_id', $currentTheme)->where('store_id',$store_id)->pluck('id')->toArray();
            }
        }
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $faqs = Faq::where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $filter_product = $request->filter_product;
        $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        $SubCategoryList = SubCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        $filter_tag = $SubCategoryList;
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        $search_products = Product::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name', 'id');
        $ApiController = new ApiController();

        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();
        $brands = ProductBrand::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        if (!$has_subcategory) {
            $filter_tag = $MainCategoryList;
        }
        $sub_category_select = $brand_select = [];
        $main_category = $request->main_category;
        $category_slug = $request->category_slug;
        $sub_category = $request->sub_category;
        $product_brand = $request->brands;
        if (!empty($main_category)) {
            if (!$has_subcategory) {
                $sub_category_select = MainCategory::where('id', $main_category)->pluck('id')->toArray();
            } else {
                $sub_category_select = SubCategory::where('maincategory_id', $main_category)->pluck('id')->toArray();
            }
        }

        if (!empty($product_brand)) {
            $brand_select = ProductBrand::where('id', $product_brand)->pluck('id')->toArray();
        }

        if (is_array($sub_category_select) && count($sub_category_select) == 0 && isset($category_slug)) {
            $sub_category_select = MainCategory::where('slug', $category_slug)->pluck('id')->toArray();
        }
        if (!empty($sub_category)) {
            $sub_category_select = [];
            $sub_category_select[] = $sub_category;
        }
        // bestseller
        $per_page = '12';
        $destination = 'web';
        $bestSeller_fun = Product::bestseller_guest($currentTheme, $store_id, $per_page, $destination);
        $bestSeller = [];
        if ($bestSeller_fun['status'] == 'success') {
            $bestSeller = $bestSeller_fun['bestseller_array'];
        }

        $products_query = Product::where('theme_id', $currentTheme)->where('product_type', null)->where('store_id', $store_id)->where('status', 1);
        if (!empty($main_category)) {
            $products_query->where('maincategory_id', $main_category);
        }
        if (!empty($sub_category)) {
            $products_query->where('subcategory_id', $sub_category);
        }
        if (count($category_ids) > 0) {
            $products_query->whereIn('maincategory_id', $category_ids);
        }

        if (!empty($product_brand)) {
            $products_query->where('brand_id', $product_brand);
        }

        $product_count = $products_query->count();
        $products = $products_query->get();

        /* For Filter */
        $min_price = 0;
        $max_price = Product::where('variant_product', 0)->orderBy('price', 'DESC')->where('product_type', null)->where('theme_id', $currentTheme)->where('store_id', $store_id)->first();
        $max_price = !empty($max_price->price) ? $max_price->price : '0';

        $currency_icon = $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency_icon = $currency = '$';
        }

        $MainCategory = MainCategory::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name', 'id');
        $MainCategory->prepend('All Products', '0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('product_type', null)->where('store_id', $store_id)->get();

        $product_list = Product::orderBy('created_at', 'asc')->where('theme_id', $currentTheme)->where('store_id', $store->id)->limit(4)->get();

        $product_tag = implode(',', $category_ids);
        $product_brand = implode(',', $brand_ids);

        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'collection_page'));
        $compact = ['slug','MainCategoryList','SubCategoryList','bestSeller','currency', 'currency_icon', 'min_price', 'max_price','product_count','has_subcategory','filter_tag','search_products','sub_category_select','featured_products','filter_product','MainCategory','homeproducts', 'products','product_list', 'brands', 'brand_select', 'product_tag', 'page_json','product_brand','store'];

        return view('front_end.sections.pages.product_list', compact($compact) + $data + $sqlData);
    }

    public function product_page_filter(Request $request, $storeSlug)
    {
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
        $theme_id = $currentTheme = $store->theme_id;
        $store_id = $store->id;
        $slug = $storeSlug;
        Theme::set($store->theme_id);
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        if ($request->ajax()) {
            $page = $request->page;
            $filter_value = $request->filter_product;
            $product_tag = $request->product_tag;
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $rating = $request->rating;

        } else {
            $page = $request->query('page', 1);
            $filter_value = $request->query('filter_product');
            $product_tag = $request->query('product_tag');
            $min_price = $request->query('min_price');
            $max_price = $request->query('max_price');
            $rating = $request->query('rating');
            // $queryParams = $request->query();
            // $page = 1;
        }
        $filter_value = $request->filter_product;
        $product_tag = $request->product_tag;
        $product_brand = $request->product_brand;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $rating = $request->rating;

        if(!empty($product_tag))
        {
            $tag = $product_tag;
            $product_tag = explode(',', $tag);
        }

        $products_query = Product::where('theme_id', $theme_id)->where('product_type', null)->where('store_id', $store_id)->where('status', 1);
        if (!empty($product_tag)) {
            if (!$has_subcategory) {
                $products_query->whereIn('maincategory_id', $product_tag);
            } else {
                $products_query->whereIn('subcategory_id', $product_tag);
            }
        }

        if (!empty($product_brand)) {
            $productBrandIds = explode(',', $product_brand);
            $products_query->whereIn('brand_id', $productBrandIds);
        }
        if (!empty($max_price)) {
            $products_query->whereBetween('price', [$min_price, $max_price]);
        }
        if (!empty($rating) && $rating != 'undefined') {
            $products_query->where('average_rating', $rating);
        }
        if (!empty($filter_value)) {
            if ($filter_value == 'best-selling') {
                // $products_query->where('tag_api','best seller');
            }
            if ($filter_value == 'trending') {
                $products_query->where('trending', '1');
            }
            if ($filter_value == 'title-ascending') {
                $products_query->orderBy('name', 'asc');
            }
            if ($filter_value == 'title-descending') {
                $products_query->orderBy('name', 'Desc');
            }
            if ($filter_value == 'price-ascending') {
                $products_query->orderBy('price', 'asc');
            }
            if ($filter_value == 'price-descending') {
                $products_query->orderBy('price', 'Desc');
            }
            if ($filter_value == 'created-ascending') {
                $products_query->orderBy('created_at', 'asc');
            }
            if ($filter_value == 'created-descending') {
                $products_query->orderBy('created_at', 'Desc');
            }
        }

        $products = $products_query->paginate(12);
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $currency_icon = $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency_icon = $currency = '$';
        }

        $setting = getAdminAllSetting();
        $defaultTimeZone = isset($setting['defult_timezone']) ? $setting['defult_timezone'] : 'Asia/Kolkata';
        date_default_timezone_set($defaultTimeZone);
        $currentDateTime = date('Y-m-d H:i:s A');
        $tax_option = TaxOption::where('store_id', $store->id)
            ->where('theme_id', $store->theme_id)
            ->pluck('value', 'name')->toArray();

        return view('front_end.sections.pages.product_list_filter', compact('tax_option', 'currentTheme', 'slug', 'products', 'currency', 'page', 'currency_icon', 'currentDateTime', 'topNavItems') + $data + $sqlData)->render();
    }

    public function product_detail(Request $request, $storeSlug, $product_slug)
    {
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        $storeId = $store->id;
        $product = Product::where('slug', $product_slug)->where('theme_id', $currentTheme)->where('store_id', $store_id)->first();
        if (!$product) {
            abort(404);
        }
        $id = $product->id;
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $storeId)->get();
        $SubCategoryList = SubCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $storeId)->get();
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        $search_products = Product::where('theme_id', $currentTheme)->where('store_id', $storeId)->get()->pluck('name', 'id');
        $ApiController = new ApiController();

        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();

        $Stocks = ProductVariant::where('product_id', $id)->first();
        if ($Stocks) {
            $minPrice = ProductVariant::where('product_id', $id)->min('price');
            $maxPrice = ProductVariant::where('product_id', $id)->max('price');

            $min_vprice = ProductVariant::where('product_id', $id)->min('variation_price');
            $max_vprice = ProductVariant::where('product_id', $id)->max('variation_price');

            $mi_price = !empty($minPrice) ? $minPrice : $min_vprice;
            $ma_price = !empty($maxPrice) ? $maxPrice : $max_vprice;
        } else {
            $mi_price = 0;
            $ma_price = 0;
        }

        $currency_icon = $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency_icon = $currency = '$';
        }

        $per_page = '12';
        $destination = 'web';
        $bestSeller_fun = Product::bestseller_guest($currentTheme, $storeId, $per_page, $destination);
        $bestSeller = [];
        if ($bestSeller_fun['status'] == 'success') {
            $bestSeller = $bestSeller_fun['bestseller_array'];
        }

        $product_review = Testimonial::where('product_id', $id)->get();

        if (!$product) {
            return redirect()->route('page.product-list', $slug)->with('error', __('Product not found.'));
        }

        $wishlist = Wishlist::where('product_id', $id)->get();
        $latest_product = Product::where('theme_id', $currentTheme)->where('store_id', $storeId)->where('product_type', null)->latest()->first();

        $MainCategory = MainCategory::where('theme_id', $currentTheme)->where('store_id', $storeId)->get()->pluck('name', 'id');
        $MainCategory->prepend('All Products', '0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('store_id', $storeId)->where('product_type', null)->get();
        $M_products = Product::whereIn('id', [$id])->first();
        $product_stocks = ProductVariant::where('product_id', $id)->where('theme_id', $currentTheme)->limit(3)->get();
        $main_pro = Product::where('maincategory_id', $M_products->category_id)->where('theme_id', $currentTheme)->where('store_id', $storeId)->where('product_type', null)->inRandomOrder()->limit(3)->get();

        $random_review = Testimonial::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $storeId)->inRandomOrder()->get();
        $reviews = Testimonial::where('status', 1)->where('theme_id', $currentTheme)->where('store_id', $storeId)->get();

        $lat_product = Product::orderBy('created_at', 'Desc')->where('theme_id', $currentTheme)->where('store_id', $storeId)->inRandomOrder()->limit(2)->get();

        $question = ProductQuestion::where('theme_id', $currentTheme)->where('product_id', $id)->where('store_id', $storeId)->get();

        $flashsales = FlashSale::where('theme_id', $currentTheme)->where('store_id', $storeId)->orderBy('created_at', 'Desc')->get();

        $setting = getAdminAllSetting();
        $defaultTimeZone = isset($setting['defult_timezone']) ? $setting['defult_timezone'] : 'Asia/Kolkata';
        date_default_timezone_set($defaultTimeZone);
        $currentDateTime = date('Y-m-d H:i:s A');

        $country_option = Country::orderBy('name', 'ASC')->pluck('name', 'id')->prepend('Select country', ' ');
        $response = Cart::cart_list_cookie($request->all(), $store->id);
        $response = json_decode(json_encode($response));
        $param = [
            'theme_id' => $store->theme_id,
            'customer_id' => !empty(\Auth::guard('customers')->user()) ? \Auth::guard('customers')->user()->id : 0,
            'slug' => $slug,
            'store_id' => $store->id,
        ];
        $request->merge($param);

        $theme = new OfertemagController();
        $payment_list_data = $theme->payment_list($request, $slug);
        $filtered_payment_list = json_decode(json_encode($payment_list_data));
        $payment_list = $payment_list_data;

        return view('front_end.sections.pages.product', compact('currentTheme', 'section', 'slug', 'product', 'MainCategoryList', 'SubCategoryList', 'currency', 'currency_icon', 'bestSeller', 'product_review', 'wishlist', 'has_subcategory', 'latest_product', 'search_products', 'featured_products', 'MainCategory', 'homeproducts', 'M_products', 'product_stocks', 'main_pro', 'lat_product', 'random_review', 'reviews', 'question', 'mi_price', 'ma_price', 'flashsales', 'currentDateTime', 'topNavItems', 'country_option', 'response', 'payment_list') + $data + $sqlData);

    }

    public function cart_page(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = GetCurrenctTheme($slug);
        if ($store) {
            $theme_id = $store->theme_id;

            $homepage_products = Product::orderBy('created_at', 'Desc')->where('theme_id', $theme_id)->get();

            $per_page = '12';
            $destination = 'web';
            $bestSeller_fun = Product::bestseller_guest($theme_id, $store->id, $store->id, $per_page, $destination);
            $bestSeller = [];
            if ($bestSeller_fun['status'] == 'success') {
                $bestSeller = $bestSeller_fun['bestseller_array'];
            }
            $data = getThemeSections($currentTheme, $slug, true, true);
            $section = (object) $data['section'];
            // Get Data from database
            $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
            $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
            $currency_icon = $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
            if (empty($currency)) {
                $currency_icon = $currency = '$';
            }

            $languages = Utility::languages();
            $MainCategory = MainCategory::where('theme_id', $theme_id)->where('store_id', getCurrentStore())->get()->pluck('name', 'id');
            $MainCategory->prepend('All Products', '0');
            $homeproducts = Product::where('theme_id', $theme_id)->where('store_id', getCurrentStore())->where('product_type', null)->get();
            $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'cart_page'));

            return view('front_end.sections.pages.cart', compact('store', 'section', 'currentTheme', 'currency', 'currantLang', 'MainCategory', 'homeproducts', 'languages', 'bestSeller', 'page_json') + $data + $sqlData);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function checkout(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }
        $theme_id = $store->theme_id;
        $currentTheme = GetCurrenctTheme($slug);
        $data = getThemeSections($currentTheme, $slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency = '$';
        }

        $languages = Utility::languages();

        $param = [
            'theme_id' => $theme_id,
            'customer_id' => !empty(\Auth::guard('customers')->user()) ? \Auth::guard('customers')->user()->id : 0,
        ];
        $request->merge($param);
        $api = new ApiController();

        $address_list_data = $api->address_list($request);
        $address_list = $address_list_data->getData();

        $country_option = Country::orderBy('name', 'ASC')->pluck('name', 'id')->prepend('Select Country', 0);
        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();
        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'checkout_page'));

        return view('front_end.sections.pages.checkout', compact('store', 'address_list', 'country_option', 'settings', 'currentTheme', 'currency', 'currantLang', 'languages', 'section', 'page_json') + $data + $sqlData);
    }

    public function order_track(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        $user = User::where('email', $request->email)->first();
        $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency = '$';
        }

        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $languages = Utility::languages();

        $pixels = PixelFields::where('store_id', $store->id)
            ->where('theme_id', $store->theme_id)
            ->get();
        $pixelScript = [];
        foreach ($pixels as $pixel) {
            $pixelScript[] = pixelSourceCode($pixel['platform'], $pixel['pixel_id']);
        }

        if (!empty($request->order_number) || !empty($request->email)) {

            $product_order_id = Order::where('store_id', $store->id)->get();
            $order_id = [];
            foreach ($product_order_id as $order) {
                $order_id[] = $order['product_order_id'];

            }
            $order_email = OrderBillingDetail::whereIn('product_order_id', $order_id)->pluck('email', 'email')->toArray();
            $order_number = Order::where('store_id', $store->id)->pluck('product_order_id', 'product_order_id')->toArray();

            if (in_array($request->email, $order_email) && in_array($request->order_number, $order_number)) {
                $order_d = OrderBillingDetail::where('email', $request->email)->where('product_order_id', $request->order_number)->first();
                $order = Order::where('id', $order_d->order_id)->where('store_id', $store->id)->first();
                $order_status = Order::where('product_order_id', $request->order_number)->where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();
            } elseif (in_array($request->email, $order_email)) {
                $order_d = OrderBillingDetail::where('email', $request->email)->first();
                $order = Order::where('id', $order_d->order_id)->where('store_id', $store->id)->first();
                $order_status = Order::where('id', $order_d->order_id)->where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();

            } elseif (in_array($request->order_number, $order_number)) {
                $order = Order::where('product_order_id', $request->order_number)->where('store_id', $store->id)->first();
                $order_status = Order::where('product_order_id', $request->order_number)->where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();

            } else {
                return view('front_end.sections.pages.order_track', compact('currentTheme', 'slug', 'currency', 'currantLang', 'languages', 'store', 'pixelScript'));

            }

            if (!isset($order)) {
                $order_detail = Order::order_detail($order->id ?? null);
            } else {
                $order_detail = Order::order_detail($order->id);
            }
            if (!empty($order)) {
                $customer = User::where('email', $order->email)->first();
            } else {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            return view('front_end.sections.pages.order_track', compact('order', 'order_status', 'order_detail', 'customer', 'slug', 'currentTheme', 'currency', 'currantLang', 'languages', 'store', 'pixelScript'));
        } else {
            return view('front_end.sections.pages.order_track', compact('currentTheme', 'slug', 'currency', 'currantLang', 'languages', 'store', 'pixelScript'));

        }

    }

    public function contactUs(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            $currency = '$';
        }

        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $languages = Utility::languages();
        $data = getThemeSections($currentTheme, $slug, true, true);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $section = (object) $data['section'];
        $page_json = arrayToObject(getInnerPageJson($currentTheme, $store->id, 'contact_page'));

        return view('front_end.sections.pages.contact-us', compact('slug', 'currentTheme', 'currency', 'currantLang', 'languages', 'section', 'store', 'page_json') + $sqlData + $data);
    }

    public function search_products(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            $return['html_data'] = null;
            $return['message'] = __('Store not found.');

            return response()->json($return);
        }
        $theme_id = $store->theme_id;

        $search_pro = $request->product;

        $products = Product::where('name', 'LIKE', '%'.$search_pro.'%')->where('store_id', $store->id)->get();
        // Check if any matching products were found
        if (!$products->isEmpty()) {
            // Create an array of product URLs
            $productData = [];

            // Populate the array with product names and URLs
            foreach ($products as $product) {
                $url = url($slug.'/product/'.$product->slug);

                $productData[] = [
                    'name' => $product->name,
                    'url' => $url,
                ];
            }

            return response()->json($productData);
        } else {
            // Handle the case where no matching products were found
            return response()->json([]);
        }
    }

    public function privacy_page(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back();
        } else {
            $currentTheme = $theme_id = $store->theme_id;
        }

        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $data = getThemeSections($currentTheme, $store->slug, true, true);

        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $ApiController = new ApiController();
        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();

        $pages_data = Page::where('theme_id', $currentTheme)->where('store_id', $store->id)->where('page_name', 'Privacy Policy')->get();

        return view('front_end.sections.pages.privacy_policys', compact('slug', 'pages_data', 'featured_products', 'topNavItems') + $data + $sqlData);
    }

    public function SoftwareDetails($slug)
    {
        $modules_all = Module::getByStatus(1);
        $modules = [];
        if (count($modules_all) > 0) {
            $modules = array_intersect_key(
                $modules_all,  // the array with all keys
                array_flip(array_rand($modules_all, (count($modules_all) < 6) ? count($modules_all) : 6)) // keys to be extracted
            );
        }
        $plan = Plan::first();

        $addon = AddOnManager::where('name', $slug)->first();

        if (!empty($addon) && !empty($addon->module)) {
            $module = Module::find($addon->module);

            if (!empty($module)) {
                try {
                    if (module_is_active('LandingPage')) {
                        return view('landingpage::marketplace.index', compact('modules', 'module', 'plan'));
                    } else {
                        return view($module->getLowerName().'::marketplace.index', compact('modules', 'module', 'plan'));
                    }
                } catch (\Throwable $th) {

                }
            }
        }
        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';

        } else {
            $layout = 'marketplace.marketplace';
        }

        return view('marketplace.detail_not_found', compact('modules', 'layout'));

    }

    public function Software(Request $request)
    {
        // Get the query parameter from the request
        $query = $request->query('query');
        // Get all modules (assuming Module::getByStatus(1) returns all modules)
        $modules = Module::getByStatus(1);

        // Filter modules based on the query parameter
        if ($query) {
            $modules = array_filter($modules, function ($module) use ($query) {
                // You may need to adjust this condition based on your requirements
                return stripos($module->getName(), $query) !== false;
            });
        }
        // Rest of your code
        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }

        return view('marketplace.software', compact('modules', 'layout'));
    }

    public function Pricing()
    {
        $admin_settings = getAdminAllSetting();
        if (module_is_active('GoogleCaptcha') && (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on') {
            config(['captcha.secret' => isset($admin_settings['google_recaptcha_secret']) ? $admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($admin_settings['google_recaptcha_key']) ? $admin_settings['google_recaptcha_key'] : '']);
        }
        if (auth()->check()) {
            if (auth()->user()->type == 'admin') {
                return redirect('plans');
            } else {
                return redirect('dashboard');
            }
        } else {
            $plan = Plan::first();
            $modules = Module::getByStatus(1);

            if (module_is_active('LandingPage')) {
                $layout = 'landingpage::layouts.marketplace';

                return view('landingpage::layouts.pricing', compact('modules', 'plan', 'layout'));

            } else {
                $layout = 'marketplace.marketplace';
            }

            return view('marketplace.pricing', compact('modules', 'plan', 'layout'));
        }
    }

    public function top_brand_category_chart(Request $request)
    {
        $tab_name = $request->tabId;
        $type = $request->type;
        if ($type == 'category') {
            if ($tab_name == '#all-category-order') {
                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'));
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();

            } elseif ($tab_name == '#today-category-order') {

                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereDate('orders.created_at', '=', \DB::raw('CURDATE()'));
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();

            } elseif ($tab_name == '#week-category-order') {
                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();

            } elseif ($tab_name == '#month-category-order') {
                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereYear('orders.created_at', now()->year)
                            ->whereMonth('orders.created_at', now()->month);
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } elseif ($tab_name == '#year-category-order') {
                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereYear('orders.created_at', now()->year);
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } else {
                $top_sales = MainCategory::select('main_categories.name as sale_name', 'main_categories.image_path as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'main_categories.id', '=', 'products.maincategory_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'));
                    })
                    ->where('main_categories.theme_id', APP_THEME())
                    ->where('main_categories.store_id', getCurrentStore())
                    ->groupBy('main_categories.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            }
        } else {
            if ($tab_name == '#all-brand-order') {
                $top_sales = ProductBrand::select('product_brands.name as sale_name', 'product_brands.logo as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'));
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } elseif ($tab_name == '#today-brand-order') {

                $top_sales = ProductBrand::select('product_brands.name as sale_name', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereDate('orders.created_at', '=', \DB::raw('CURDATE()'));
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();

            } elseif ($tab_name == '#week-brand-order') {
                $top_sales = ProductBrand::select('product_brands.name as sale_name', 'product_brands.logo as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } elseif ($tab_name == '#month-brand-order') {
                $top_sales = ProductBrand::select('product_brands.name as sale_name', 'product_brands.logo as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereYear('orders.created_at', now()->year)
                            ->whereMonth('orders.created_at', now()->month);
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } elseif ($tab_name == '#year-brand-order') {
                $top_sales = ProductBrand::select('product_brands.name as sale_name', 'product_brands.logo as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'))
                            ->whereYear('orders.created_at', now()->year);
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            } else {
                $top_sales = ProductBrand::select('product_brands.name as sale_name', 'product_brands.logo as sale_image_path', \DB::raw('SUM(orders.final_price) as total_sale'))
                    ->join('products', 'product_brands.id', '=', 'products.brand_id')
                    ->join('orders', function ($join) {
                        $join->on('products.id', '=', \DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(orders.product_id, ',', numbers.n), ',', -1)"))
                            ->crossJoin(\DB::raw('(SELECT 1 + a.N + b.N * 10 AS n FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b) AS numbers'));
                    })
                    ->where('product_brands.theme_id', APP_THEME())
                    ->where('product_brands.store_id', getCurrentStore())
                    ->groupBy('product_brands.name')
                    ->orderBy('total_sale', 'desc')
                    ->get();
            }
        }
        $html = '';
        $html = view('order.brand_category_chart', compact('tab_name', 'top_sales'))->render();

        $return['html'] = $html;
        $return['tab_name'] = $tab_name;
        $return['type'] = $type;

        return response()->json($return);
    }

    public function best_selling_brand_chart(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        //$currency = Utility::GetValueByName('CURRENCY');
        $currency = Utility::GetValueByName('defult_currancy_symbol', $store->theme_id, $store->id);
        if (empty($currency)) {
            //$currency = Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id);
            $currency = '$';
        }
        if ($request->chart_data == 'last-month') {
            $data = 'last-month';
            $lastMonth = Carbon::now()->subMonth();
            $prevMonth = strtotime('-1 month');
            $start = strtotime(date('Y-m-01', $prevMonth));
            $end = strtotime(date('Y-m-t', $prevMonth));

            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', null)->whereYear('regiester_date', date('Y'))->get()->count();
            $totaluser = 0;
            $guest = '';

            $lastDayofMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();
            $lastday = date('j', strtotime($lastDayofMonth));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            foreach ($orders as $order) {
                $day = (int) date('j', strtotime($order->DATE)); // Extract day of the month

                $netSaleTotalArray[$day][] = (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float) $order->final_price;
                $CouponTotalArray[$day][] = (float) $order['coupon_price'];
                $ShippingTotalArray[$day][] = (float) $order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale += (float) $order->final_price;
                $TotalNetSale += (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount += (float) $order['coupon_price'];
                $TotalShippingCharge += (float) $order->delivery_price;
            }

            for ($i = 1; $i <= $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }

            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {
            $start = strtotime(date('Y-m-01'));
            $end = strtotime(date('Y-m-t'));
            $day = (int) date('j', strtotime($end));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            foreach ($orders as $order) {
                $day = (int) date('j', strtotime($order->DATE));
                $userTotalArray[$day][] = $order->order_date;

                $netSaleTotalArray[$day][] = (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float) $order->final_price;
                $CouponTotalArray[$day][] = (float) $order['coupon_price'];
                $ShippingTotalArray[$day][] = (float) $order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale += (float) $order->final_price;
                $TotalNetSale += (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount += (float) $order['coupon_price'];
                $TotalShippingCharge += (float) $order->delivery_price;
            }
            $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $lastday = date('j', strtotime($lastDayofMonth));

            for ($i = 1; $i <= $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $startDate = now()->subDays(6);

            $TotalOrder = 0;
            $totalDuePurchaseorder = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            $monthList = [];
            $previous_week = strtotime('-1 week +1 day');

            for ($i = 0; $i <= 7 - 1; $i++) {
                $date = date('Y-m-d', $previous_week);
                $previous_week = strtotime(date('Y-m-d', $previous_week).' +1 day');
                $monthList[] = __(date('d-M', strtotime($date)));

                $ordersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get();
                $TotalOrder += $ordersForDate->count();
                $totalPurchasedItemsForDate = 0;

                foreach ($ordersForDate as $order) {
                    $products = json_decode($order->product_json, true);

                    $totalProductQuantity = array_reduce($products, function ($carry, $product) {
                        return $carry + intval($product['qty']);
                    }, 0);
                    $totalPurchasedItemsForDate += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $PurchasedItemTotal[] = $totalPurchasedItemsForDate;

                $totalOrdersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->count();

                $GrossSaleTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');

                $NetSaleTotal[] = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $CouponTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $ShippingTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');
                $TotalOrderCount[] = $totalOrdersForDate;

                $averageGrossSales[] = $totalOrdersForDate > 0 ? ($GrossSaleTotal[count($GrossSaleTotal) - 1] / $totalOrdersForDate) : 0;
                $averageNetSales[] = $totalOrdersForDate > 0 ? ($NetSaleTotal[count($NetSaleTotal) - 1] / $totalOrdersForDate) : 0;

                $TotalgrossSale += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');
                $TotalNetSale += Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $TotalCouponAmount += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $TotalShippingCharge += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');
                $TotalOrderCount[] = $totalOrdersForDate;
            }
        } elseif ($request->chart_data == 'year') {

            $TotalOrder = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();

            $orders = Order::selectRaw('orders.*,MONTH(order_date) as month,YEAR(order_date) as year');
            $start = strtotime(date('Y-01'));
            $end = strtotime(date('Y-12'));
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $order = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereYear('order_date', date('Y'))
                ->get()->count();

            $totalDuePurchaseorder = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            foreach ($orders as $order) {
                $netSaleTotalArray[$order->month][] = (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$order->month][] = (float) $order->final_price;
                $CouponTotalArray[$order->month][] = (float) $order['coupon_price'];
                $ShippingTotalArray[$order->month][] = (float) $order->delivery_price;
                $OrderTotalArray[$order->month][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$order->month][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale += (float) $order->final_price;
                $TotalNetSale += (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount += (float) $order['coupon_price'];
                $TotalShippingCharge += (float) $order->delivery_price;
            }
            for ($i = 1; $i <= 12; $i++) {

                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $monthlySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $average = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageGrossSales[] = $average;

                $monthlySales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $netsaleaverage = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageNetSales[] = $netsaleaverage;
            }
            $monthList = $month = $this->yearMonth();
        } else {
            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $start_date = date('Y-m-d', strtotime($date_range[0]));
                    $end_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {

                $form_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }
            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->whereDate('order_date', '>=', $form_date)->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            $monthLists = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $monthLists = Order::whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $monthLists = $monthLists->get();

            foreach ($monthLists as $monthLists_date) {
                $data[] = date('y-n-j', strtotime($monthLists_date->order_date));
                $data_month[] = date('Y-m-d', strtotime($monthLists_date->order_date));
            }
            if (!empty($data) && is_array($data)) {
                $List = array_values(array_unique($data));
                $monthList_data = $List;
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            } else {
                $List = [];
                $monthList_data = [];
                $data_month[] = date('y-n-j');
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            }

            foreach ($orders as $order) {
                $day = date('y-n-j', strtotime($order->DATE));
                $userTotalArray[$day][] = date('y-n-j', strtotime($order->order_date));

                $netSaleTotalArray[$day][] = (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float) $order->final_price;
                $CouponTotalArray[$day][] = (float) $order['coupon_price'];
                $ShippingTotalArray[$day][] = (float) $order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale += (float) $order->final_price;
                $TotalNetSale += (float) ($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount += (float) $order['coupon_price'];
                $TotalShippingCharge += (float) $order->delivery_price;
            }

            if (!empty($data) && is_array($data)) {
                foreach ($monthList_data as $month) {
                    $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                    $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                    $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                    $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;
                    $TotalOrderCount[] = array_key_exists($month, $OrderTotalArray) ? count($OrderTotalArray[$month]) : 0;

                    $PurchasedItemTotal[] = array_key_exists($month, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$month]) : 0;

                    $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                    $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                    $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                    $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
                }
            } else {
                $month = date('y-n-j');
                $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;
                $TotalOrderCount[] = array_key_exists($month, $OrderTotalArray) ? count($OrderTotalArray[$month]) : 0;

                $PurchasedItemTotal[] = array_key_exists($month, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$month]) : 0;

                $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
        }

        $html = '';
        $html = view('reports.order_chart_data', compact('TotalOrder', 'PurchasedProductItemTotal', 'TotalgrossSale', 'currency', 'TotalNetSale', 'TotalCouponAmount', 'TotalShippingCharge'))->render();

        $return['html'] = $html;

        $return['TotalOrderCount'] = $TotalOrderCount;
        $return['NetSaleTotal'] = $NetSaleTotal;
        $return['AverageNetSales'] = $averageNetSales;
        $return['GrossSaleTotal'] = $GrossSaleTotal;
        $return['AverageGrossSales'] = $averageGrossSales;
        $return['PurchasedItemTotal'] = $PurchasedItemTotal;
        $return['ShippingTotal'] = $ShippingTotal;
        $return['CouponTotal'] = $CouponTotal;
        $return['monthList'] = $monthList;
        Session::put('order_line_chart_report', $return);

        return response()->json($return);
    }
}
