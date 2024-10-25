<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user() && auth()->user()->isAbleTo('Manage Store Setting'))
        {
            $user = Auth::user();
            $setting = getAdminAllSetting($user->id,getCurrentStore(), APP_THEME());
            $store = Store::find(getCurrentStore());
            $slug = $store->slug;
            $plan = Plan::find($user->plan_id);
            $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
		    $serverIp = $subdomain_name = $subdomain_Ip = $subdomainPointing = $domainip = $domainPointing = null;
            if($setting) {
                if(isset($setting['domains']) && $setting['domains']) {
                    $serverIp   = $_SERVER['SERVER_ADDR'];
                    $domain = $setting['domains'];
                    if (isset($domain) && !empty($domain)) {
                        $domainip = gethostbyname($domain);
                    }
                    if ($serverIp == $domainip) {
                        $domainPointing = 1;
                    } else {
                        $domainPointing = 0;
                    }
                } else {
                    $serverIp   = $_SERVER['SERVER_ADDR'];
                    $domain = $serverIp;
                    $domainip = gethostbyname($domain);
                    $domainPointing = 0;
                }
                $serverName = str_replace(
                    [
                        'http://',
                        'https://',
                    ],
                    '',
                    env('APP_URL')
                );
                $serverIp   = gethostbyname($serverName);

                if ($serverIp == $_SERVER['SERVER_ADDR']) {
                    $serverIp;
                } else {
                    $serverIp = request()->server('SERVER_ADDR');
                }

                $app_url                     = trim(env('APP_URL'), '/');

                $store_settings['store_url'] = $app_url . '/' . $slug;
                // Remove the http://, www., and slash(/) from the URL
                $input = env('APP_URL');

                // If URI is like, eg. www.way2tutorial.com/
                $input = trim($input, '/');
                // If not have http:// or https:// then prepend it
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);

                $serverIp   = $_SERVER['SERVER_ADDR'];

                if (!empty($setting['subdomain']) || !empty($urlParts['host'])) {
                    $subdomain_Ip   = gethostbyname($urlParts['host']);
                    if ($serverIp == $subdomain_Ip) {
                        $subdomainPointing = 1;
                    } else {
                        $subdomainPointing = 0;
                    }
                    // Remove www.
                    $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                } else {
                    $subdomain_Ip = $urlParts['host'] ?? (request()->ip() ?? null);
                    $subdomainPointing = 0;
                    $subdomain_name = str_replace(
                        [
                            'http://',
                            'https://',
                        ],
                        '',
                        env('APP_URL')
                    );
                }
            }

            if (empty($serverIp)) {
                $serverIp = request()->ip();
            }
            // Get Inner Page Json
            $json_data_array =  getInnerPageJson($theme_id, $store->id);

            $json_data = $json_data_array['order-complete'] ?? [];
            $product_json_data = $json_data_array['product_page'] ?? [];
            $blog_json_data = $json_data_array['blog_page'] ?? [];
            $cart_json_data = $json_data_array['cart_page'] ?? [];
            $checkout_json_data = $json_data_array['checkout_page'] ?? [];
            $contact_json_data = $json_data_array['contact_page'] ?? [];
            $faq_json_data = $json_data_array['faq_page'] ?? [];
            $collection_json_data = $json_data_array['collection_page'] ?? [];
            $article_json_data = $json_data_array['article_page'] ?? [];
            $languages = Utility::languages();
            $app_setting_tab = session()->get('app_setting_tab');
            if (empty($app_setting_tab)) {
                $app_setting_tab = 'pills-home-tab';
            }
            return view('AppSetting.index',compact('setting','plan','json_data','slug','serverIp','subdomain_name','subdomain_Ip','subdomainPointing','domainip','domainPointing', 'languages','store','product_json_data','blog_json_data','cart_json_data','checkout_json_data','contact_json_data','faq_json_data','collection_json_data','article_json_data','app_setting_tab'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        session()->put('app_setting_tab', $request->app_setting_tab);
		$theme_id = APP_THEME();
        $json = $request->array;
        $array = $request->array;
        $dir        = 'themes/'.APP_THEME().'/uploads';
        $new_array = [];
        foreach ($array as $key => $jsn) {
            foreach ($jsn['inner-list'] as $IN_key => $js) {
                $new_array[$jsn['section_slug']][$js['field_slug']] = $js['field_default_text'];
                if($js['field_type'] == 'multi file upload') {
                    if(!empty($js['multi_image'])) {
                        foreach ($js['multi_image'] as $key_file => $file) {
                            $theme_name = $theme_id;
                            $theme_image = $file;
                            
                            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                            $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                            $img_path = '';
                            if( !empty($upload['flag']) && $upload['flag'] == 1)
                            {
                                $img_path = $upload['image_path'];
                            }
                            $array[$key]["inner-list"][$IN_key]['image_path'][] = $img_path;
                            $array[$key][$js['field_slug']][$key_file]['image'] = $img_path;
                            $array[$key][$js['field_slug']][$key_file]['field_prev_text'] = $img_path;
                        }

                        $next_key_p_image = !empty($key_file) ? $key_file : 0;
                        if(!empty($jsn['prev_image'])) {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                $next_key_p_image = $next_key_p_image + 1;
                                $array[$key][$js['field_slug']][$next_key_p_image]['image'] = $p_value;
                                $array[$key][$js['field_slug']][$next_key_p_image]['field_prev_text'] = $p_value;
                            }
                        }
                    } else {
                        if(!empty($jsn['prev_image'])) {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                $array[$key][$js['field_slug']][$p_key]['image'] = $p_value;
                                $array[$key][$js['field_slug']][$p_key]['field_prev_text'] = $p_value;
                            }
                        }
                    }
                }
                if($js['field_type'] == 'photo upload')
                {
                    if ($jsn['array_type'] == 'multi-inner-list')
                    {
                        $k = 0;
                        $img_path_multi = [];
                        for ($i = 0; $i < $jsn['loop_number']; $i++) {
                            $img_path_multi[$i] = '';
                            if(empty($array[$key][$js['field_slug']][$i]['field_prev_text'])) {
                                $array[$key][$js['field_slug']][$i]['field_prev_text'] = $js['field_default_text'];
                                $img_path_multi[$i] = $js['field_default_text'];
                            }else{
                                $img_path_multi[$i] = $array[$key][$js['field_slug']][$i]['field_prev_text'];
                            }
                            if (!empty($array[$key][$js['field_slug']][$i]['image']) && gettype($array[$key][$js['field_slug']][$i]['image']) == 'object')
                            {
                                $theme_name = $theme_id;
                                $theme_image = $array[$key][$js['field_slug']][$i]['image'];
                                
                                $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                                $img_path = '';
                                if( !empty($upload['flag']) && $upload['flag'] == 1)
                                {
                                    $img_path = $upload['image_path'];
                                }
                                $array[$key][$js['field_slug']][$i]['image'] = $img_path;
                                $array[$key][$js['field_slug']][$i]['field_prev_text'] = $img_path;
                                $img_path_multi[$i] = $img_path;
                            }
                        }
                        $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path_multi;
                    }
                    else
                    {
                        if (gettype($js['field_default_text']) == 'object') {
                            $theme_name = $theme_id;
                            $theme_image = $js['field_default_text'];
                           
                            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                            $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                            $img_path = '';
                            if( !empty($upload['flag']) && $upload['flag'] == 1)
                            {
                                $img_path = $upload['image_path'];
                            }
                            $array[$key]['inner-list'][$IN_key]['field_default_text'] = $img_path;
                            $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path;
                        }
                    }
                }
            }
        }
        AppSetting::updateOrInsert(
            ['theme_id' => $theme_id, 'page_name' => 'main', 'store_id' => getCurrentStore()], // Where condition
            ['theme_id' => $theme_id, 'page_name' => 'main','store_id' => getCurrentStore(), 'theme_json' => json_encode($array), 'theme_json_api' => json_encode($new_array)]   // Update or Insert
        );

        return redirect()->back()->with('success', __('App setting set successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AppSetting $appSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppSetting $appSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppSetting $appSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppSetting $appSetting)
    {
        //
    }

    public function seoSettings(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $validator = \Validator::make(
            $request->all(),
            [
                'google_analytic'   => 'required',
                'fbpixel_code'      => 'required',
                'metakeyword'       => 'required',
                'metadesc'          => 'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);

        $post['google_analytic']    = $request->google_analytic;
        $post['fbpixel_code']       = $request->fbpixel_code;
        $post['metakeyword']        = $request->metakeyword;
        $post['metadesc']           = $request->metadesc;

        $dir = 'themes/' . APP_THEME() . '/uploads';
        if ($request->metaimage) {
            $theme_image = $request->metaimage;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->metaimage->getClientOriginalName();
            $path = Utility::upload_file($request, 'metaimage', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'metaimage', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['metaimage'] = $path['url'];

            }
        }
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore()
            ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => \Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Seo setting successfully saved.');
    }

    public function shippingLabelSettings(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $validator = \Validator::make(
            $request->all(),
            [
                'store_address'   => 'required',
                'store_city'      => 'required',
                'store_state'       => 'required',
                'store_zipcode'          => 'required',
                'store_country'          => 'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);


        $post['store_address']    = $request->store_address;
        $post['store_city']       = $request->store_city;
        $post['store_state']        = $request->store_state;
        $post['store_zipcode']           = $request->store_zipcode;
        $post['store_country']           = $request->store_country;
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore()
            ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => \Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Shipping Label setting successfully saved.');
    }

    public function product_page_setting(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $page_name = $request->section_name ?? ( $request->page_name ?? null);
        $dir        = 'themes/'.APP_THEME().'/uploads';
        if(empty($page_name)) {
            return redirect()->back()->with('error', __('Page name not found.'));
        }

        $array = $request->array;
        if($page_name == 'home_page_web') {
            $array = $request->array;
        }
        $decodedData = json_encode($array, true);
        $new_array = [];
        foreach ($array as $key => $jsn) {
            if (isset($jsn['inner-list']) && is_array($jsn['inner-list'])) {
                foreach ($jsn['inner-list'] as $IN_key => $js) {
                    $new_array[$jsn['section_slug']][$js['field_slug']] = $js['field_default_text'];
                    if($js['field_type'] == 'multi file upload') {
                        if(!empty($js['multi_image'])) {
                            foreach ($js['multi_image'] as $key_file => $file) {
                                $theme_name = $theme_id;
                                $theme_image = $file;

                                $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                                $img_path = '';
                                if( !empty($upload['flag']) && $upload['flag'] == 1)
                                {
                                    $img_path = $upload['image_path'];
                                }
                                $array[$key][$js['field_slug']][$key_file]['image'] = $img_path;
                                $array[$key][$js['field_slug']][$key_file]['field_prev_text'] = $img_path;
                            }

                            $next_key_p_image = !empty($key_file) ? $key_file : 0;
                            if(!empty($jsn['prev_image'])) {
                                foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                    $next_key_p_image = $next_key_p_image + 1;
                                    $array[$key][$js['field_slug']][$next_key_p_image]['image'] = $p_value;
                                    $array[$key][$js['field_slug']][$next_key_p_image]['field_prev_text'] = $p_value;
                                }
                            }
                        } else {
                            if(!empty($jsn['prev_image'])) {
                                foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                    $array[$key][$js['field_slug']][$p_key]['image'] = $p_value;
                                    $array[$key][$js['field_slug']][$p_key]['field_prev_text'] = $p_value;
                                }
                            }
                        }
                    }
                    if($js['field_type'] == 'photo upload')
                    {
                        if ($jsn['array_type'] == 'multi-inner-list')
                        {
                            $k = 0;
                            $img_path_multi = [];
                            for ($i = 0; $i < $jsn['loop_number']; $i++) {
                                $img_path_multi[$i] = '';
                                if(empty($array[$key][$js['field_slug']][$i]['field_prev_text'])) {
                                    $array[$key][$js['field_slug']][$i]['field_prev_text'] = $js['field_default_text'];
                                    $img_path_multi[$i] = $js['field_default_text'];
                                }
                                if (!empty($array[$key][$js['field_slug']][$i]['image']) && gettype($array[$key][$js['field_slug']][$i]['image']) == 'object')
                                {
                                    $theme_name = $theme_id;
                                    $theme_image = $array[$key][$js['field_slug']][$i]['image'];

                                    $image_size = File::size($theme_image);
                                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                                    if ($result == 1)
                                    {
                                        $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                        $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);
                                        $img_path = '';
                                        if( !empty($upload['flag']) && $upload['flag'] == 1)
                                        {
                                            $img_path = $upload['image_path'];
                                        }
                                    }
                                    else{
                                        return redirect()->back()->with('error', $result);
                                    }

                                    $array[$key][$js['field_slug']][$i]['image'] = $img_path;
                                    $array[$key][$js['field_slug']][$i]['field_prev_text'] = $img_path;
                                    $img_path_multi[$i] = $img_path;
                                }
                            }
                            $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path_multi;
                        }
                        else
                        {
                            if (gettype($js['field_default_text']) == 'object') {
                                $theme_name = $theme_id;
                                $theme_image = $js['field_default_text'];

                                $image_size = File::size($theme_image);
                                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                                if ($result == 1)
                                {
                                    $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                    $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);
                                    $img_path = '';
                                    if( !empty($upload['flag']) && $upload['flag'] == 1)
                                    {
                                        $img_path = $upload['image_path'];
                                    }
                                }
                                else{
                                    return redirect()->back()->with('error', $result);
                                }

                                $array[$key]['inner-list'][$IN_key]['field_default_text'] = $img_path;
                                $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path;
                            }
                        }
                    }
                }
            }

        }

        // Upload section inside image
        if (isset($array['section']['image']['text']) && !is_array($array['section']['image']['text']) && gettype($array['section']['image']['text']) == 'object') {
            $theme_image = $array['section']['image']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['image']['text'] = $upload['data']['image_path'];
                $array['section']['image']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        AppSetting::updateOrCreate(
            ['theme_id' => $theme_id, 'page_name' => $page_name, 'store_id' => getCurrentStore() ], // Where condition
            ['theme_id' => $theme_id, 'page_name' => $page_name,'store_id' => getCurrentStore(), 'theme_json' => json_encode($array), 'theme_json_api' => json_encode($new_array)]   // Update or Insert
        );

        return redirect()->back()->with('success', __(ucfirst(str_replace('_',' ', $page_name)). ' setting saved successfully.'));
    }

    public function ThemeSettings(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $store = Store::find(getCurrentStore());
        $settings = Setting::where('theme_id', $store->theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();

        if ($request->theme_name && ($request->theme_name != $store->name)) {
            $store->name = $request->theme_name;
            $store->save();
        }

        if ($request->default_language && ($request->default_language != $store->default_language)) {
            $store->default_language = $request->default_language;
            $store->save();
        }

        if ($request->enable_domain == 'enable_domain') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'domains' => 'required',
                ]
            );
        }
        if ($request->enable_domain == 'enable_subdomain') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subdomain' => 'required',
                ]
            );
        }

        $user = \Auth::user();
        $theme_id = !empty($store->theme_id) ? $store->theme_id : '';
        $post = $request->all();
        $dir = 'themes/' . APP_THEME() . '/uploads';
        $settingQuery = Setting::query();
        if ($request->theme_logo) {
            $theme_image = $request->theme_logo;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->theme_logo->getClientOriginalName();
            $path = Utility::upload_file($request, 'theme_logo', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'theme_logo', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['theme_logo'] = $path['url'] ?? null;
            }
        }
        if ($request->invoice_logo) {
            $theme_image = $request->invoice_logo;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->invoice_logo->getClientOriginalName();
            $path = Utility::upload_file($request, 'invoice_logo', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'invoice_logo', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['invoice_logo'] = $path['url'] ?? null;
            }
        }
        if ($request->theme_favicon) {
            $theme_image = $request->theme_favicon;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->theme_favicon->getClientOriginalName();
            $path = Utility::upload_file($request, 'theme_favicon', $fileName, $dir, []);


            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'theme_favicon', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['theme_favicon'] = $path['url'] ?? null;
            }
        }

        if ($request->theme_image) {
		    $theme_image = $request->theme_image;
		    $fileName = rand(10, 100) . '_' . time() . "_" . $request->theme_image->getClientOriginalName();
		    $path = Utility::upload_file($request, 'theme_image', $fileName, $dir, []);


		    if ($path['flag'] == '0') {
		        return redirect()->back()->with('error', $path['msg']);
		    } else {
		        $where = ['name' => 'theme_image', 'theme_id' => $theme_id];
		        $Setting = Setting::where($where)->first();

		        if (!empty($Setting)) {
		            if (File::exists(base_path($Setting->value))) {
		                File::delete(base_path($Setting->value));
		            }
		        }

		        $post['theme_image'] = $path['url'] ?? null;
		    }
		}
        
        if ($request->enable_domain && $request->enable_domain == 'enable_domain') {
            // Remove the http://, www., and slash(/) from the URL
            $input = $request->domains;
            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);
            // Remove www.
            $post['domains'] = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
        }
        if ($request->enable_domain && $request->enable_domain == 'enable_subdomain') {
            // Remove the http://, www., and slash(/) from the URL
            $input = env('APP_URL');

            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);

            if (is_array($urlParts) && isset($urlParts['host'])) {
                // Remove www.
                $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                // Output way2tutorial.com
                $post['subdomain'] = $request->subdomain . '.' . $subdomain_name;
            } else {
                // Handle the case where $urlParts is not an array or doesn't contain the expected elements
                // You can log an error, throw an exception, or handle it according to your application logic
                // For example:
                $post['subdomain'] = null; // or any default value you prefer
            }


        }


        if ($request->enable_domain) {
            $settings['enable_storelink'] = ($request->enable_domain == 'enable_storelink' || empty($request->enable_domain)) ? 'on' : 'off';
            $settings['enable_domain'] = ($request->enable_domain == 'enable_domain') ? 'on' : 'off';
            $settings['enable_subdomain'] = ($request->enable_domain == 'enable_subdomain') ? 'on' : 'off';
            $post['enable_storelink'] = $settings['enable_storelink'];
            $post['enable_domain'] = $settings['enable_domain'];
            $post['enable_subdomain'] = $settings['enable_subdomain'];
        }
        
        if ($request->additional_notes) {
            $additional_notes = $request->has('additional_notes') ? $request->additional_notes : 'off';
            $post['additional_notes'] = $additional_notes;
        }

        if ($request->is_checkout_login_required) {
            $is_checkout_login_required = $request->has('is_checkout_login_required') ? $request->is_checkout_login_required : 'off';
            $post['is_checkout_login_required'] = $is_checkout_login_required;
        }

        if ($request->store_address) {
            $post['store_address'] = $request->store_address;
        }

        if ($request->store_city) {
            $post['store_city'] = $request->store_city;
        }

        if ($request->store_state) {
            $post['store_state'] = $request->store_state;
        }

        if ($request->store_zipcode) {
            $post['store_zipcode'] = $request->store_zipcode;
        }

        if ($request->store_country) {
            $post['store_country'] = $request->store_country;
        }

        if ($request->coupon_list_enable) {
            $post['coupon_list_enable'] = $request->coupon_list_enable;
        }

        if ($request->enable_spam_prevent) {
            $post['enable_spam_prevent'] = $request->enable_spam_prevent;
        }

        if ($request->product_csvswich_is_enable) {
            $post['product_csvswich_is_enable'] = $request->product_csvswich_is_enable;
        }

        if ($request->products_pdf_enable) {
            $post['products_pdf_enable'] = $request->products_pdf_enable;
        }
       
        if ($request->cart_quantity_control_enable) {
            $post['cart_quantity_control_enable'] = $request->cart_quantity_control_enable ?? 'off';
        }
        
        if (!empty($request->theme_name) || !empty($request->email) || !empty($request->google_analytic) || !empty($request->fbpixel_code) || !empty($request->storejs) || !empty($request->storecss)) {

            // if (!isset($request->google_analytic)) {
            //     $post['google_analytic'] = !empty($request->google_analytic) ? $request->google_analytic : '';
            // }
            // if (!isset($request->fbpixel_code)) {
            //     $post['fbpixel_code'] = !empty($request->fbpixel_code) ? $request->fbpixel_code : '';
            // }
            if ($request->storejs) {
                $post['storejs'] = !empty($request->storejs) ? $request->storejs : '';
            }
            if ($request->storecss) {
                $post['storecss'] = !empty($request->storecss) ? $request->storecss : '';
            }
        }

        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore(),
                'created_by'=>auth()->user()->id
               ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Settings successfully updated.'));
    }

    public function FirebaseSettings(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';

        $firebase_enabled = !empty($request->firebase_enabled) ? $request->firebase_enabled : 'off';
        $fcm_Key = !empty($request->fcm_Key) ? $request->fcm_Key : '';

        $post['firebase_enabled'] = $firebase_enabled;
        $post['fcm_Key'] = $fcm_Key;
        $settingQuery = Setting::query();
        foreach($post as $key => $data)
        {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,'created_by'=>auth()->user()->id],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    public function MobileScreenContent()
    {
        // ie: /var/www/laravel/app/storage/json/filename.json
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : env('DATA_INSERT_APP_THEME');
        // Main page
        $path = base_path('themes/'.$theme_id.'/theme_json/homepage.json');
        $json = json_decode(file_get_contents($path), true);

        $setting_json = AppSetting::select('theme_json')
                            ->where('theme_id', $theme_id)
                            ->where('page_name', 'main')
                            ->where('store_id', getCurrentStore())
                            ->first();
        if(!empty($setting_json)) {
            $json = json_decode($setting_json->theme_json, true);
        }

        // Product Banner page
        $product_banner_json_path = base_path('theme_json/product-banner.json');
        $product_banner_json = json_decode(file_get_contents($product_banner_json_path), true);

        $setting_product_banner_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'product_banner')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($setting_product_banner_json)) {
            $product_banner_json = json_decode($setting_product_banner_json->theme_json, true);
        }

        // Order Complete page
        $order_complete_json_path = base_path('theme_json/order-complete.json');
        $order_complete_json = json_decode(file_get_contents($order_complete_json_path), true);

        $setting_order_complete_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'order_complete')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($setting_order_complete_json)) {
            $order_complete_json = json_decode($setting_order_complete_json->theme_json, true);
        }

        // Home pagw (WEBSITE)
        $homepage_web_json = [];
        $homepage_web_json_path = base_path('themes/'.$theme_id.'/theme_json/web/homepage.json');
        if(file_exists($homepage_web_json_path)) {
            $homepage_web_json = json_decode(file_get_contents($homepage_web_json_path), true);
        }

        $homepage_web_complete_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'home_page_web')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($homepage_web_complete_json)) {
            $homepage_web_json = json_decode($homepage_web_complete_json->theme_json, true);
        }


        // loyality program json
        $loyality_program_json = Utility::loyality_program_json($theme_id,getCurrentStore());

        $theme_name = !empty(APP_THEME()) ? APP_THEME() : env('DATA_INSERT_APP_THEME');
        $settings = Setting::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('value', 'name')->toArray();
        $store = Store::find(getCurrentStore());
        $slug = $store->slug;
        if(empty($settings))
        {
            $settings = Utility::Seting();
        }
        $themes = [];

        $user = auth()->user();
        if($user->type == 'admin')
        {
            $plan = Plan::find($user->plan);
            if(!empty($plan->themes))
            {
              $themes =  explode(',',$plan->themes);
            }
        }

        $app_setting_tab = session()->get('app_setting_tab');
        if (empty($app_setting_tab)) {
            $app_setting_tab = 'pills-home-tab';
        }

        $compact = ['json', 'product_banner_json', 'order_complete_json', 'homepage_web_json', 'loyality_program_json','slug','settings','themes','user','app_setting_tab'];
        return view('AppSetting.mobile_content', compact($compact));
    }

    public function SiteSetting(Request $request)
    {
        session()->put('app_setting_tab', $request->app_setting_tab);
        $validator = \Validator::make(
            $request->all(),
            [
                'date_format' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $post['date_format'] = $request->date_format;

        $settingQuery = Setting::query();
        // Iterate over the data and insert/update in the 'settings' table
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    private function getThemeJson($theme_id, $page_name, $default_path) {
        // Default data
        $json_data = json_decode(file_get_contents($default_path), true);
    
        // Fetch setting from database
        $setting_json_data = AppSetting::select('theme_json')
            ->where('theme_id', $theme_id)
            ->where('page_name', $page_name)
            ->where('store_id', getCurrentStore())
            ->first();
    
        if (!empty($setting_json_data)) {
            $json_data = json_decode($setting_json_data->theme_json, true);
        }
    
        return $json_data;
    }
    
    private function uploadThemeMedia($request, $theme_id, $theme_image, $dir) {

        $image_size = File::size($theme_image);
        $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
        if ($result == 1)
        {
            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
            $upload = Utility::upload_file($request, $theme_image, $fileName, $dir, [], $theme_image);
            $img_path = '';
            return ["error"=>false,"data"=> $upload];
        } else {
            return ["error"=>true,"data"=> []];
        }
}
}
