<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use \WhichBrowser\Parser;
use App\Mail\TestMail;
use App\Models\Customer;
use App\Models\PixelFields;
use Illuminate\Support\Facades\Cookie;
use App\Models\{Webhook, WhatsappMessage, Plan ,Country,State,City};
use App\Models\Tax;
use App\Models\TaxOption;
use App\Models\EmailTemplate;
use App\Models\ApikeySetiings;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('setting.index');
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

    public function StorageSettings(Request $request)
    {
        session()->put(['setting_tab' => 'storage_setting']);
        $theme_id = APP_THEME();
        if (isset($request->storage_setting) && $request->storage_setting == 'local') {

            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 's3') {
            $request->validate(
                [
                    's3_key' => 'required',
                    's3_secret' => 'required',
                    's3_region' => 'required',
                    's3_bucket' => 'required',
                    's3_url' => 'required',
                    's3_endpoint' => 'required',
                    's3_max_upload_size' => 'required',
                    's3_storage_validation' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $post['s3_key'] = $request->s3_key;
            $post['s3_secret'] = $request->s3_secret;
            $post['s3_region'] = $request->s3_region;
            $post['s3_bucket'] = $request->s3_bucket;
            $post['s3_url'] = $request->s3_url;
            $post['s3_endpoint'] = $request->s3_endpoint;
            $post['s3_max_upload_size'] = $request->s3_max_upload_size;
            $s3_storage_validation = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation'] = $s3_storage_validation;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 'wasabi') {
            $request->validate(
                [
                    'wasabi_key' => 'required',
                    'wasabi_secret' => 'required',
                    'wasabi_region' => 'required',
                    'wasabi_bucket' => 'required',
                    'wasabi_url' => 'required',
                    'wasabi_root' => 'required',
                    'wasabi_max_upload_size' => 'required',
                    'wasabi_storage_validation' => 'required',
                ]
            );
            $post['storage_setting'] = $request->storage_setting;
            $post['wasabi_key'] = $request->wasabi_key;
            $post['wasabi_secret'] = $request->wasabi_secret;
            $post['wasabi_region'] = $request->wasabi_region;
            $post['wasabi_bucket'] = $request->wasabi_bucket;
            $post['wasabi_url'] = $request->wasabi_url;
            $post['wasabi_root'] = $request->wasabi_root;
            $post['wasabi_max_upload_size'] = $request->wasabi_max_upload_size;
            $wasabi_storage_validation = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation'] = $wasabi_storage_validation;
        }

        $settingQuery = Setting::query();
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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function BusinessSettings(Request $request)
    {
        session()->put(['setting_tab' => 'brand_setting']);
        // Get the authenticated user
        $user = auth()->user();

        // Get the theme ID and directory
        $theme_id = APP_THEME();
        $dir = 'themes/' . APP_THEME() . '/uploads';

        // Get data from the request
        $post = $request->all();

        $SITE_RTL = !isset($request->SITE_RTL) ? 'off' : 'on';
        // Check user type
        if (auth()->user()->type == 'super admin') {
            $dir =  Storage::url('uploads/logo');
            if ($request->logo_dark) {
                $theme_image = $request->logo_dark;
                $defaultName = 'logo-dark.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->logo_dark->getClientOriginalName();
                $path = Utility::upload_file($request, 'logo_dark', $fileName, $dir, []);
                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'logo_dark', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();
                    if (!empty($Setting) && $defaultName != 'logo-dark.png') {
                        $image_path = 'uploads/logo/' . $fileName;
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                    }
                    $post['logo_dark'] = $path['url'];
                }
            }
            if ($request->logo_light) {
                $theme_image = $request->logo_light;
                $defaultName = 'logo-light.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->logo_light->getClientOriginalName();
                $path = Utility::upload_file($request, 'logo_light', $fileName, $dir, []);

                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'logo_light', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting) && $defaultName != 'logo-light.png') {
                        $image_path = 'uploads/logo/' . $fileName;
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }
                    $post['logo_light'] = $path['url'];
                }
            }

            if ($request->favicon) {
                $theme_image = $request->favicon;
                $defaultName = 'favicon.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->favicon->getClientOriginalName();
                $path = Utility::upload_file($request, 'favicon', $fileName, $dir, []);

                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'favicon', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting) && $defaultName != 'favicon.png') {
                        $image_path = 'uploads/logo/' . $fileName;
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }
                    $post['favicon'] = $path['url'];
                }
            }
        } else {
            if ($request->logo_dark) {
                $theme_image = $request->logo_dark;
                $defaultName = 'logo-dark.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->logo_dark->getClientOriginalName();
                $path = Utility::upload_file($request, 'logo_dark', $fileName, $dir, []);

                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'logo_dark', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting) && $defaultName != 'logo-dark.png') {
                        $removePath = Utility::remove_file($Setting->value);
                        if ($removePath['flag'] == '0') {
                            return redirect()->back()->with('error', $removePath['msg']);
                        }
                    }
                    $post['logo_dark'] = $path['url'];
                }
            }
            if ($request->logo_light) {
                $theme_image = $request->logo_light;
                $defaultName = 'logo-light.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->logo_light->getClientOriginalName();
                $path = Utility::upload_file($request, 'logo_light', $fileName, $dir, []);

                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'logo_light', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting) && $defaultName != 'logo-light.png') {
                        $removePath = Utility::remove_file($Setting->value);
                        if ($removePath['flag'] == '0') {
                            return redirect()->back()->with('error', $removePath['msg']);
                        }
                    }
                    $post['logo_light'] = $path['url'];
                }
            }
            if ($request->favicon) {
                $theme_image = $request->favicon;
                $defaultName = 'favicon.png';
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->favicon->getClientOriginalName();
                $path = Utility::upload_file($request, 'favicon', $fileName, $dir, []);

                if ($path['flag'] == '0') {
                    return redirect()->back()->with('error', $path['msg']);
                } else {
                    $where = ['name' => 'favicon', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting) && $defaultName != 'favicon.png') {
                        $removePath = Utility::remove_file($Setting->value);
                        if ($removePath['flag'] == '0') {
                            return redirect()->back()->with('error', $removePath['msg']);
                        }
                    }
                    $post['favicon'] = $path['url'];
                }
            }
        }

        $default_language = $request->has('default_language') ? $request->default_language : 'en';
        if (auth()->user()->type == 'super admin') {
            $user = auth()->user();
            $user->default_language = $default_language;
            $user->save();

            $store = Store::where('id', $user->current_store)->first();
            $store->default_language = $default_language;
            $store->save();
        } else {
            $user = auth()->user();
            $user->default_language = $default_language;
            $user->save();

            $store = Store::where('id', $user->current_store)->first();
            $store->default_language = $default_language;
            $store->save();
        }

        // if (!empty($request->title_text) || !empty($request->footer_text) || !empty($request->color) || !empty($request->email_verification) || !empty($request->display_landing)) {
            $SITE_RTL = $request->has('SITE_RTL') ? $request->SITE_RTL : 'off';
            $post['SITE_RTL'] = $SITE_RTL;

            $SIGNUP = $request->has('SIGNUP') ? $request->SIGNUP : 'off';
            $post['SIGNUP'] = $SIGNUP;

            $taxes = $request->has('taxes') ? $request->taxes : 'off';
            $post['taxes'] = $taxes;

            $display_landing = $request->has('display_landing') ? $request->display_landing : 'off';
            $post['display_landing'] = $display_landing;


            $email_verification = $request->has('email_verification') ? $request->email_verification : 'off';
            $post['email_verification'] = $email_verification;

            if (!isset($request->cust_theme_bg)) {
                $post['cust_theme_bg'] = 'off';
            }
            if (!isset($request->cust_darklayout)) {
                $post['cust_darklayout'] = 'off';
            }

            if(isset($request->color) && $request->color_flag == 'false')
            {
                $post['color'] = $request->color;
            } elseif(isset($request->custom_color) && $request->color_flag == 'true')
            {
                $post['custom_color'] = $request->custom_color;
                $post['color'] = $request->custom_color;
            }
            // else
            // {
            //     $post['color'] = $request->custom_color;
            // }
            unset($post['default_language']);
            $settingQuery = Setting::query();
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
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            }
        // }
        return redirect()->back()->with('success', __('Brand setting successfully updated.'));
    }

    public function saveEmailSettings(Request $request)
    {
        session()->put(['setting_tab' => 'email_setting']);
        // Validate the incoming request data
        $validator = \Validator::make(
            $request->all(),
            [
                'mail_driver' => 'required|string|max:50',
                'mail_host' => 'required|string|max:50',
                'mail_port' => 'required|string|max:50',
                'mail_username' => 'required|string|max:50',
                'mail_password' => 'required|string|max:50',
                'mail_encryption' => 'required|string|max:50',
                'mail_from_address' => 'required|string|max:50',
                'mail_from_name' => 'required|string|max:50',
            ]
        );

        // If validation fails, redirect back with the first error message
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        // Prepare data for database insertion/update
        $post['email_setting'] = $request->email_setting ?? 'SMTP';
        $post['MAIL_DRIVER'] = $request->mail_driver;
        $post['MAIL_HOST'] = $request->mail_host;
        $post['MAIL_PORT'] = $request->mail_port;
        $post['MAIL_USERNAME'] = $request->mail_username;
        $post['MAIL_PASSWORD'] = $request->mail_password;
        $post['MAIL_ENCRYPTION'] = $request->mail_encryption;
        $post['MAIL_FROM_NAME'] = $request->mail_from_name;
        $post['MAIL_FROM_ADDRESS'] = $request->mail_from_address;

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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        // Redirect back with success message
        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    public function TestMail(Request $request)
    {
        $email_setting = $request->all();
        $settings = Setting::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('value', 'name')->toArray();
        $user = auth()->user();

        $data = [];
        $data['mail_driver'] = $request->mail_driver;
        $data['mail_host'] = $request->mail_host;
        $data['mail_port'] = $request->mail_port;
        $data['mail_username'] = $request->mail_username;
        $data['mail_password'] = $request->mail_password;
        $data['mail_encryption'] = $request->mail_encryption;
        $data['mail_from_address'] = $request->mail_from_address;
        $data['mail_from_name'] = $request->mail_from_name;

        return view('setting.test_mail', compact('email_setting', 'settings', 'data'));
    }

    public function testSendMail(Request $request)
    {
        session()->put(['setting_tab' => 'email_setting']);
        $validator = \Validator::make(
            $request->all(),
            [
                'mail_driver' => 'required',
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_from_address' => 'required',
                'mail_from_name' => 'required',
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json(
                [
                    'is_success' => false,
                    'message' => $messages->first(),
                ]
            );
        }

        try {
            SetConfigEmail($request);

            Mail::to($request->email)->send(new TestMail($request));

            return response()->json(
                [
                    'is_success' => true,
                    'message' => __('Email send Successfully'),
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }

    }

    public function PaymentSetting(Request $request)
    {
        session()->put(['setting_tab' => 'payment_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $store_id = !empty(getCurrentStore()) ? getCurrentStore() : '1';

        // CURRENCY
        // $validator = \Validator::make(
        //     $request->all(),
        //     [
        //         'CURRENCY_NAME' => 'required',
        //         'CURRENCY'      => 'required',
        //     ]
        // );
        // if ($validator->fails()) {
        //     $messages = $validator->getMessageBag();
        //     return redirect()->back()->with('error', $messages->first());
        // }
        if (isset($request->CURRENCY)) {
            $post['CURRENCY'] = $request->CURRENCY;
        }
        if (isset($request->CURRENCY_NAME)) {
            $post['CURRENCY_NAME'] = $request->CURRENCY_NAME;
        }


        // COD
        if ($request->is_cod_enabled == 'on' && !empty($request->cod_image)) {
            $image = upload_theme_image($theme_id, $request->cod_image);
            if ($image['status'] == false) {
                return redirect()->back()->with('error', $image['message']);
            } else {
                $where = ['name' => 'cod_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['cod_image'] = $image['image_path'];
            }
        }
        $post['is_cod_enabled'] = $request->is_cod_enabled ? $request->is_cod_enabled : 'off';
        $post['cod_info']       = $request->cod_info;


        // Bank Transfer
        if ($request->is_bank_transfer_enabled == 'on' && !empty($request->bank_transfer_image)) {
            $bank_transfer_image = upload_theme_image($theme_id, $request->bank_transfer_image);
            if ($bank_transfer_image['status'] == false) {
                return redirect()->back()->with('error', $bank_transfer_image['message']);
            } else {
                $where = ['name' => 'bank_transfer_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['bank_transfer_image'] = $bank_transfer_image['image_path'];
            }
        }
        $post['is_bank_transfer_enabled']   = $request->is_bank_transfer_enabled ? $request->is_bank_transfer_enabled : 'off';
        $post['bank_transfer']              = $request->bank_transfer;



        //    Stripe
        if ($request->is_stripe_enabled == 'on' && !empty($request->stripe_image)) {
            $stripe_image = upload_theme_image($theme_id, $request->stripe_image);
            if ($stripe_image['status'] == false) {
                return redirect()->back()->with('error', $stripe_image['message']);
            } else {
                $where = ['name' => 'stripe_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['stripe_image'] = $stripe_image['image_path'];
            }
        }
        if($request->is_stripe_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_stripe_enabled'         => 'required',
                    'stripe_publishable_key'    => 'required',
                    'stripe_secret_key'         => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_stripe_enabled']          = !empty($request->is_stripe_enabled) ? $request->is_stripe_enabled : 'off';
            $post['stripe_publishable_key']     = !empty($request->stripe_publishable_key) ? $request->stripe_publishable_key : '';
            $post['stripe_secret_key']          = !empty($request->stripe_secret_key) ? $request->stripe_secret_key : '';
            $post['stripe_unfo']                = !empty($request->stripe_unfo) ? $request->stripe_unfo : '';
        }else{
            $post['is_stripe_enabled']          = 'off';
        }

        // paystack
        if ($request->is_paystack_enabled == 'on' && !empty($request->paystack_image)) {
            $paystack_image     = upload_theme_image($theme_id, $request->paystack_image);
            if ($paystack_image['status'] == false) {
                return redirect()->back()->with('error', $paystack_image['message']);
            } else {
                $where      = ['name' => 'paystack_image', 'theme_id' => $theme_id];
                $Setting    = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paystack_image'] = $paystack_image['image_path'];
            }
        }
        if($request->is_paystack_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paystack_enabled'   => 'required',
                    'paystack_public_key'   => 'required',
                    'paystack_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paystack_enabled']    = !empty($request->is_paystack_enabled) ? $request->is_paystack_enabled : 'off';
            $post['paystack_public_key']    = !empty($request->paystack_public_key) ? $request->paystack_public_key : '';
            $post['paystack_secret_key']    = !empty($request->paystack_secret_key) ? $request->paystack_secret_key : '';
            $post['paystack_unfo']          = !empty($request->paystack_unfo) ? $request->paystack_unfo : '';
        }else{
            $post['is_paystack_enabled']    = 'off';
        }


        // Razorpay
        if ($request->is_razorpay_enabled == 'on' && !empty($request->razorpay_image)) {
            $razorpay_image = upload_theme_image($theme_id, $request->razorpay_image);
            if ($razorpay_image['status'] == false) {
                return redirect()->back()->with('error', $razorpay_image['message']);
            } else {
                $where = ['name' => 'razorpay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['razorpay_image'] = $razorpay_image['image_path'];
            }
        }
        if($request->is_razorpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_razorpay_enabled'   => 'required',
                    'razorpay_public_key'   => 'required',
                    'razorpay_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_razorpay_enabled']    = !empty($request->is_razorpay_enabled) ? $request->is_razorpay_enabled : 'off';
            $post['razorpay_public_key']    = !empty($request->razorpay_public_key) ? $request->razorpay_public_key : '';
            $post['razorpay_secret_key']    = !empty($request->razorpay_secret_key) ? $request->razorpay_secret_key : '';
            $post['razorpay_unfo']          = !empty($request->razorpay_unfo) ? $request->razorpay_unfo : '';
        }else{
            $post['is_razorpay_enabled']    = 'off';
        }



        // Mercado Pago
        if ($request->is_mercado_enabled == 'on' && !empty($request->mercado_image)) {
            $mercado_image = upload_theme_image($theme_id,  $request->mercado_image);
            if ($mercado_image['status'] == false) {
                return redirect()->back()->with('error', $mercado_image['message']);
            } else {
                $where = ['name' => 'mercado_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['mercado_image'] = $mercado_image['image_path'];
            }
        }
        if($request->is_mercado_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_mercado_enabled'    => 'required',
                    'mercado_mode'          => 'required',
                    'mercado_access_token'  => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_mercado_enabled']    = !empty($request->is_mercado_enabled) ? $request->is_mercado_enabled : 'off';
            $post['mercado_mode']           = !empty($request->mercado_mode) ? $request->mercado_mode : 'sandbox';
            $post['mercado_access_token']   = !empty($request->mercado_access_token) ? $request->mercado_access_token : '';
            $post['mercado_unfo']           = !empty($request->mercado_unfo) ? $request->mercado_unfo : '';
        }else{
            $post['is_mercado_enabled']    = 'off';
        }

        // Skrill
        if ($request->is_skrill_enabled == 'on' && !empty($request->skrill_image)) {
            $skrill_image = upload_theme_image($theme_id, $request->skrill_image);
            if ($skrill_image['status'] == false) {
                return redirect()->back()->with('error', $skrill_image['message']);
            } else {
                $where = ['name' => 'skrill_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['skrill_image'] = $skrill_image['image_path'];
            }
        }
        if($request->is_skrill_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_skrill_enabled' => 'required',
                    'skrill_email'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_skrill_enabled']   = !empty($request->is_skrill_enabled) ? $request->is_skrill_enabled : 'off';
            // $post['skrill_mode']         = !empty($request->skrill_mode) ? $request->skrill_mode : 'sandbox';
            $post['skrill_email']        = !empty($request->skrill_email) ? $request->skrill_email : '';
            $post['skrill_unfo']         = !empty($request->skrill_unfo) ? $request->skrill_unfo : '';
        }else{
            $post['is_skrill_enabled']    = 'off';
        }


        // PaymentWall
        if ($request->is_paymentwall_enabled == 'on' && !empty($request->paymentwall_image)) {
            $paymentwall_image = upload_theme_image($theme_id, $request->paymentwall_image);
            if ($paymentwall_image['status'] == false) {
                return redirect()->back()->with('error', $paymentwall_image['message']);
            } else {
                $where = ['name' => 'paymentwall_image', 'store_id' => $store_id, 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paymentwall_image'] = $paymentwall_image['image_path'];
            }
        }
        if($request->is_paymentwall_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paymentwall_enabled'    => 'required',
                    'paymentwall_public_key'    => 'required',
                    'paymentwall_private_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paymentwall_enabled']     = !empty($request->is_paymentwall_enabled) ? $request->is_paymentwall_enabled : 'off';
            $post['paymentwall_public_key']     = !empty($request->paymentwall_public_key) ? $request->paymentwall_public_key : '';
            $post['paymentwall_private_key']    = !empty($request->paymentwall_private_key) ? $request->paymentwall_private_key : '';
            $post['paymentwall_unfo']           = !empty($request->paymentwall_unfo) ? $request->paymentwall_unfo : '';
        }else{
            $post['is_paymentwall_enabled']    = 'off';
        }


        // Paypal
        if ($request->is_paypal_enabled == 'on' && !empty($request->paypal_image)) {
            $paypal_image = upload_theme_image($theme_id, $request->paypal_image);
            if ($paypal_image['status'] == false) {
                return redirect()->back()->with('error', $paypal_image['message']);
            } else {
                $where = ['name' => 'paypal_image', 'store_id' => $store_id, 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paypal_image'] = $paypal_image['image_path'];
            }
        }
        if($request->is_paypal_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paypal_enabled'     => 'required',
                    'paypal_client_id'      => 'required',
                    'paypal_secret_key'     => 'required',
                    'paypal_mode'           => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paypal_enabled']      = !empty($request->is_paypal_enabled) ? $request->is_paypal_enabled : 'off';
            $post['paypal_client_id']       = !empty($request->paypal_client_id) ? $request->paypal_client_id : '';
            $post['paypal_secret_key']      = !empty($request->paypal_secret_key) ? $request->paypal_secret_key : '';
            $post['paypal_mode']            = !empty($request->paypal_mode) ? $request->paypal_mode : '';
            $post['paypal_unfo']            = !empty($request->paypal_unfo) ? $request->paypal_unfo : '';
        }else{
            $post['is_paypal_enabled']    = 'off';
        }

        // Flutterwave
        if ($request->is_flutterwave_enabled == 'on' && !empty($request->flutterwave_image)) {
            $flutterwave_image = upload_theme_image($theme_id, $request->flutterwave_image);
            if ($flutterwave_image['status'] == false) {
                return redirect()->back()->with('error', $flutterwave_image['message']);
            } else {
                $where = ['name' => 'flutterwave_image', 'store_id' => $store_id, 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['flutterwave_image'] = $flutterwave_image['image_path'];
            }
        }
        if($request->is_flutterwave_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_flutterwave_enabled'    => 'required',
                    'flutterwave_public_key'    => 'required',
                    'flutterwave_secret_key'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_flutterwave_enabled'] = !empty($request->is_flutterwave_enabled) ? $request->is_flutterwave_enabled : 'off';
            $post['flutterwave_public_key'] = !empty($request->flutterwave_public_key) ? $request->flutterwave_public_key : '';
            $post['flutterwave_secret_key'] = !empty($request->flutterwave_secret_key) ? $request->flutterwave_secret_key : '';
            $post['flutterwave_unfo'] = !empty($request->flutterwave_unfo) ? $request->flutterwave_unfo : '';
        }else{
            $post['is_flutterwave_enabled']    = 'off';
        }

        // Paytm
        if ($request->is_paytm_enabled == 'on' && !empty($request->paytm_image)) {
            $paytm_image = upload_theme_image($theme_id, $request->paytm_image);
            if ($paytm_image['status'] == false) {
                return redirect()->back()->with('error', $paytm_image['message']);
            } else {
                $where = ['name' => 'paytm_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paytm_image'] = $paytm_image['image_path'];
            }
        }
        if($request->is_paytm_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paytm_enabled'      => 'required',
                    'paytm_merchant_id'     => 'required',
                    'paytm_merchant_key'    => 'required',
                    'paytm_industry_type'   => 'required',
                    'paytm_mode'            => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paytm_enabled'] = !empty($request->is_paytm_enabled) ? $request->is_paytm_enabled : 'off';
            $post['paytm_merchant_id'] = !empty($request->paytm_merchant_id) ? $request->paytm_merchant_id : '';
            $post['paytm_merchant_key'] = !empty($request->paytm_merchant_key) ? $request->paytm_merchant_key : '';
            $post['paytm_industry_type'] = !empty($request->paytm_industry_type) ? $request->paytm_industry_type : '';
            $post['paytm_mode'] = !empty($request->paytm_mode) ? $request->paytm_mode : '';
            $post['paytm_unfo'] = !empty($request->paytm_unfo) ? $request->paytm_unfo : '';
        }else{
            $post['is_paytm_enabled']    = 'off';
        }

        // mollie
        if ($request->is_mollie_enabled == 'on' && !empty($request->mollie_image)) {
            $mollie_image = upload_theme_image($theme_id, $request->mollie_image);
            if ($mollie_image['status'] == false) {
                return redirect()->back()->with('error', $mollie_image['message']);
            } else {
                $where = ['name' => 'mollie_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['mollie_image'] = $mollie_image['image_path'];
            }
        }
        if($request->is_mollie_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_mollie_enabled'    => 'required',
                    'mollie_api_key'       => 'required',
                    'mollie_profile_id'    => 'required',
                    'mollie_partner_id'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_mollie_enabled'] = !empty($request->is_mollie_enabled) ? $request->is_mollie_enabled : 'off';
            $post['mollie_api_key'] = !empty($request->mollie_api_key) ? $request->mollie_api_key : '';
            $post['mollie_profile_id'] = !empty($request->mollie_profile_id) ? $request->mollie_profile_id : '';
            $post['mollie_partner_id'] = !empty($request->mollie_partner_id) ? $request->mollie_partner_id : '';
            $post['mollie_unfo'] = !empty($request->mollie_unfo) ? $request->mollie_unfo : '';
        }else{
            $post['is_mollie_enabled']    = 'off';
        }

        // coingate
        if ($request->is_coingate_enabled == 'on' && !empty($request->coingate_image)) {
            $coingate_image = upload_theme_image($theme_id, $request->coingate_image);
            if ($coingate_image['status'] == false) {
                return redirect()->back()->with('error', $coingate_image['message']);
            } else {
                $where = ['name' => 'coingate_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['coingate_image'] = $coingate_image['image_path'];
            }
        }
        if($request->is_coingate_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_coingate_enabled'    => 'required',
                    'coingate_mode'          => 'required',
                    'coingate_auth_token'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_coingate_enabled'] = !empty($request->is_coingate_enabled) ? $request->is_coingate_enabled : 'off';
            $post['coingate_mode'] = !empty($request->coingate_mode) ? $request->coingate_mode : '';
            $post['coingate_auth_token'] = !empty($request->coingate_auth_token) ? $request->coingate_auth_token : '';
            $post['coingate_unfo'] = !empty($request->coingate_unfo) ? $request->coingate_unfo : '';
        }else{
            $post['is_coingate_enabled']    = 'off';
        }

        // sspay
        if ($request->is_sspay_enabled == 'on' && !empty($request->sspay_image)) {
            $sspay_image = upload_theme_image($theme_id, $request->sspay_image);
            if ($sspay_image['status'] == false) {
                return redirect()->back()->with('error', $sspay_image['message']);
            } else {
                $where = ['name' => 'sspay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['sspay_image'] = $sspay_image['image_path'];
            }
        }
        if($request->is_sspay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_sspay_enabled'    => 'required',
                    'sspay_category_code' => 'required',
                    'sspay_secret_key'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_sspay_enabled'] = !empty($request->is_sspay_enabled) ? $request->is_sspay_enabled : 'off';
            $post['sspay_category_code'] = !empty($request->sspay_category_code) ? $request->sspay_category_code : '';
            $post['sspay_secret_key'] = !empty($request->sspay_secret_key) ? $request->sspay_secret_key : '';
            $post['sspay_unfo'] = !empty($request->sspay_unfo) ? $request->sspay_unfo : '';
        }else{
            $post['is_sspay_enabled']    = 'off';
        }

        // Toyyibpay
        if ($request->is_toyyibpay_enabled == 'on' && !empty($request->toyyibpay_image)) {
            $toyyibpay_image = upload_theme_image($theme_id, $request->toyyibpay_image);
            if ($toyyibpay_image['status'] == false) {
                return redirect()->back()->with('error', $toyyibpay_image['message']);
            } else {
                $where = ['name' => 'toyyibpay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['toyyibpay_image'] = $toyyibpay_image['image_path'];
            }
        }
        if($request->is_toyyibpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_toyyibpay_enabled'    => 'required',
                    'toyyibpay_category_code' => 'required',
                    'toyyibpay_secret_key'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_toyyibpay_enabled'] = !empty($request->is_toyyibpay_enabled) ? $request->is_toyyibpay_enabled : 'off';
            $post['toyyibpay_category_code'] = !empty($request->toyyibpay_category_code) ? $request->toyyibpay_category_code : '';
            $post['toyyibpay_secret_key'] = !empty($request->toyyibpay_secret_key) ? $request->toyyibpay_secret_key : '';
            $post['toyyibpay_unfo'] = !empty($request->toyyibpay_unfo) ? $request->toyyibpay_unfo : '';
        }else{
            $post['is_toyyibpay_enabled']    = 'off';
        }

        // paytabs
        if ($request->is_paytabs_enabled == 'on' && !empty($request->paytabs_image)) {
            $paytabs_image = upload_theme_image($theme_id, $request->paytabs_image);
            if ($paytabs_image['status'] == false) {
                return redirect()->back()->with('error', $paytabs_image['message']);
            } else {
                $where = ['name' => 'paytabs_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paytabs_image'] = $paytabs_image['image_path'];
            }
        }
        if($request->is_paytabs_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paytabs_enabled'    => 'required',
                    'paytabs_profile_id'    => 'required',
                    'paytabs_server_key'    => 'required',
                    'paytabs_region'        => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paytabs_enabled'] = !empty($request->is_paytabs_enabled) ? $request->is_paytabs_enabled : 'off';
            $post['paytabs_profile_id'] = !empty($request->paytabs_profile_id) ? $request->paytabs_profile_id : '';
            $post['paytabs_server_key'] = !empty($request->paytabs_server_key) ? $request->paytabs_server_key : '';
            $post['paytabs_region'] = !empty($request->paytabs_region) ? $request->paytabs_region : '';
            $post['paytabs_unfo'] = !empty($request->paytabs_unfo) ? $request->paytabs_unfo : '';
        }else{
            $post['is_paytabs_enabled']    = 'off';
        }

        // Iyzipay
        if ($request->is_iyzipay_enabled == 'on' && !empty($request->iyzipay_image)) {
            $iyzipay_image = upload_theme_image($theme_id, $request->iyzipay_image);
            if ($iyzipay_image['status'] == false) {
                return redirect()->back()->with('error', $iyzipay_image['message']);
            } else {
                $where = ['name' => 'iyzipay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['iyzipay_image'] = $iyzipay_image['image_path'];
            }
        }
        if($request->is_iyzipay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_iyzipay_enabled'    => 'required',
                    'iyzipay_private_key'   => 'required',
                    'iyzipay_secret_key'    => 'required',
                    'iyzipay_mode'          => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_iyzipay_enabled'] = !empty($request->is_iyzipay_enabled) ? $request->is_iyzipay_enabled : 'off';
            $post['iyzipay_private_key'] = !empty($request->iyzipay_private_key) ? $request->iyzipay_private_key : '';
            $post['iyzipay_secret_key'] = !empty($request->iyzipay_secret_key) ? $request->iyzipay_secret_key : '';
            $post['iyzipay_mode'] = !empty($request->iyzipay_mode) ? $request->iyzipay_mode : '';
            $post['iyzipay_unfo'] = !empty($request->iyzipay_unfo) ? $request->iyzipay_unfo : '';
        }else{
            $post['is_iyzipay_enabled']    = 'off';
        }

        // PayFast
        if ($request->is_payfast_enabled == 'on' && !empty($request->payfast_image)) {
            $payfast_image = upload_theme_image($theme_id, $request->payfast_image);
            if ($payfast_image['status'] == false) {
                return redirect()->back()->with('error', $payfast_image['message']);
            } else {
                $where = ['name' => 'payfast_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['payfast_image'] = $payfast_image['image_path'];
            }
        }
        if($request->is_payfast_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_payfast_enabled'        => 'required',
                    'payfast_merchant_id'       => 'required',
                    'payfast_salt_passphrase'   => 'required',
                    'payfast_merchant_key'      => 'required',
                    'payfast_mode'              => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_payfast_enabled'] = !empty($request->is_payfast_enabled) ? $request->is_payfast_enabled : 'off';
            $post['payfast_merchant_id'] = !empty($request->payfast_merchant_id) ? $request->payfast_merchant_id : '';
            $post['payfast_salt_passphrase'] = !empty($request->payfast_salt_passphrase) ? $request->payfast_salt_passphrase : '';
            $post['payfast_merchant_key'] = !empty($request->payfast_merchant_key) ? $request->payfast_merchant_key : '';
            $post['payfast_mode'] = !empty($request->payfast_mode) ? $request->payfast_mode : '';
            $post['payfast_unfo'] = !empty($request->payfast_unfo) ? $request->payfast_unfo : '';
        }else{
            $post['is_payfast_enabled']    = 'off';
        }


        // Benefit
        if ($request->is_benefit_enabled == 'on' && !empty($request->benefit_image)) {
            $benefit_image = upload_theme_image($theme_id, $request->benefit_image);
            if ($benefit_image['status'] == false) {
                return redirect()->back()->with('error', $benefit_image['message']);
            } else {
                $where = ['name' => 'benefit_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['benefit_image'] = $benefit_image['image_path'];
            }
        }
        if($request->is_benefit_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_benefit_enabled'    => 'required',
                    'benefit_secret_key'    => 'required',
                    'benefit_private_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_benefit_enabled'] = !empty($request->is_benefit_enabled) ? $request->is_benefit_enabled : 'off';
            $post['benefit_secret_key'] = !empty($request->benefit_secret_key) ? $request->benefit_secret_key : '';
            $post['benefit_private_key'] = !empty($request->benefit_private_key) ? $request->benefit_private_key : '';
            $post['benefit_unfo'] = !empty($request->benefit_unfo) ? $request->benefit_unfo : '';
        }else{
            $post['is_benefit_enabled']    = 'off';
        }

        // Cashfree
        if ($request->is_cashfree_enabled == 'on' && !empty($request->cashfree_image)) {
            $cashfree_image1 = $request->cashfree_image;
            $cashfree_image = upload_theme_image($theme_id, $cashfree_image1);
            if ($cashfree_image['status'] == false) {
                return redirect()->back()->with('error', $cashfree_image['message']);
            } else {
                $where = ['name' => 'cashfree_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['cashfree_image'] = $cashfree_image['image_path'];
            }
        }
        if($request->is_cashfree_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_cashfree_enabled'   => 'required',
                    'cashfree_secret_key'   => 'required',
                    'cashfree_key'          => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_cashfree_enabled'] = !empty($request->is_cashfree_enabled) ? $request->is_cashfree_enabled : 'off';
            $post['cashfree_secret_key'] = !empty($request->cashfree_secret_key) ? $request->cashfree_secret_key : '';
            $post['cashfree_key'] = !empty($request->cashfree_key) ? $request->cashfree_key : '';
            $post['cashfree_unfo'] = !empty($request->cashfree_unfo) ? $request->cashfree_unfo : '';
        }else{
            $post['is_cashfree_enabled']    = 'off';
        }

        // Aamarpay
        if ($request->is_aamarpay_enabled == 'on' && !empty($request->aamarpay_image)) {
            $aamarpay_image = upload_theme_image($theme_id, $request->aamarpay_image);
            if ($aamarpay_image['status'] == false) {
                return redirect()->back()->with('error', $aamarpay_image['message']);
            } else {
                $where = ['name' => 'aamarpay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['aamarpay_image'] = $aamarpay_image['image_path'];
            }
        }
        if($request->is_aamarpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_aamarpay_enabled'    => 'required',
                    'aamarpay_signature_key' => 'required',
                    'aamarpay_description'   => 'required',
                    'aamarpay_store_id'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_aamarpay_enabled'] = !empty($request->is_aamarpay_enabled) ? $request->is_aamarpay_enabled : 'off';
            $post['aamarpay_signature_key'] = !empty($request->aamarpay_signature_key) ? $request->aamarpay_signature_key : '';
            $post['aamarpay_description'] = !empty($request->aamarpay_description) ? $request->aamarpay_description : '';
            $post['aamarpay_store_id'] = !empty($request->aamarpay_store_id) ? $request->aamarpay_store_id : '';
            $post['aamarpay_unfo'] = !empty($request->aamarpay_unfo) ? $request->aamarpay_unfo : '';
        }else{
            $post['is_aamarpay_enabled']    = 'off';
        }

        // Telegram
        if (\Auth::user()->type == 'admin') {
            if ($request->is_telegram_enabled == 'on' && !empty($request->telegram_image)) {
                $telegram_image = upload_theme_image($theme_id, $request->telegram_image);
                if ($telegram_image['status'] == false) {
                    return redirect()->back()->with('error', $telegram_image['message']);
                } else {
                    $where = ['name' => 'telegram_image', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting)) {
                        if (File::exists(storage_path($Setting->value))) {
                            File::delete(storage_path($Setting->value));
                        }
                    }
                    $post['telegram_image'] = $telegram_image['image_path'];
                }
            }
            if($request->is_telegram_enabled == 'on'){
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'is_telegram_enabled'   => 'required',
                        'telegram_access_token' => 'required',
                        'telegram_chat_id'      => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $post['is_telegram_enabled'] = !empty($request->is_telegram_enabled) ? $request->is_telegram_enabled : 'off';
                $post['telegram_access_token'] = !empty($request->telegram_access_token) ? $request->telegram_access_token : '';
                $post['telegram_chat_id'] = !empty($request->telegram_chat_id) ? $request->telegram_chat_id : '';
                $post['telegram_unfo'] = !empty($request->telegram_unfo) ? $request->telegram_unfo : '';
            }else{
                $post['is_telegram_enabled']    = 'off';
            }
        }

        // Whatsapp
        if (auth()->user()->type == 'admin') {
            if (!empty($request->whatsapp_number)) {
                $validator = \Validator::make($request->all(), ['whatsapp_number' => ['required', 'regex:/^\+[1-9]\d{1,14}$/'],]);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
            }
            if ($request->is_whatsapp_enabled == 'on' && !empty($request->whatsapp_image)) {
                $whatsapp_image1 = $request->whatsapp_image;
                $whatsapp_image = upload_theme_image($theme_id, $whatsapp_image1);
                if ($whatsapp_image['status'] == false) {
                    return redirect()->back()->with('error', $whatsapp_image['message']);
                } else {
                    $where = ['name' => 'whatsapp_image', 'theme_id' => $theme_id];
                    $Setting = Setting::where($where)->first();

                    if (!empty($Setting)) {
                        if (File::exists(storage_path($Setting->value))) {
                            File::delete(storage_path($Setting->value));
                        }
                    }
                    $post['whatsapp_image'] = $whatsapp_image['image_path'];
                }
            }
            $post['is_whatsapp_enabled'] = !empty($request->is_whatsapp_enabled) ? $request->is_whatsapp_enabled : 'off';
            $post['whatsapp_number'] = !empty($request->whatsapp_number) ? $request->whatsapp_number : '';
            $post['whatsapp_unfo'] = !empty($request->whatsapp_unfo) ? $request->whatsapp_unfo : '';
        }

        // Pay TR
        if ($request->is_paytr_enabled == 'on' && !empty($request->paytr_image)) {
            $paytr_image = upload_theme_image($theme_id, $request->paytr_image);
            if ($paytr_image['status'] == false) {
                return redirect()->back()->with('error', $paytr_image['message']);
            } else {
                $where = ['name' => 'paytr_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paytr_image'] = $paytr_image['image_path'];
            }
        }
        if($request->is_paytr_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paytr_enabled'   => 'required',
                    'paytr_merchant_id'  => 'required',
                    'paytr_salt_key'     => 'required',
                    'paytr_merchant_key' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_paytr_enabled'] = !empty($request->is_paytr_enabled) ? $request->is_paytr_enabled : 'off';
            $post['paytr_merchant_id'] = !empty($request->paytr_merchant_id) ? $request->paytr_merchant_id : '';
            $post['paytr_salt_key'] = !empty($request->paytr_salt_key) ? $request->paytr_salt_key : '';
            $post['paytr_merchant_key'] = !empty($request->paytr_merchant_key) ? $request->paytr_merchant_key : '';
            $post['paytr_unfo'] = !empty($request->paytr_unfo) ? $request->paytr_unfo : '';
        }else{
            $post['is_paytr_enabled']    = 'off';
        }

        // Yookassa
        if ($request->is_yookassa_enabled == 'on' && !empty($request->yookassa_image)) {
            $yookassa_image = upload_theme_image($theme_id, $request->yookassa_image);
            if ($yookassa_image['status'] == false) {
                return redirect()->back()->with('error', $yookassa_image['message']);
            } else {
                $where = ['name' => 'yookassa_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['yookassa_image'] = $yookassa_image['image_path'];
            }
        }
        if($request->is_yookassa_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_yookassa_enabled'   => 'required',
                    'yookassa_shop_id_key'  => 'required',
                    'yookassa_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_yookassa_enabled'] = !empty($request->is_yookassa_enabled) ? $request->is_yookassa_enabled : 'off';
            $post['yookassa_shop_id_key'] = !empty($request->yookassa_shop_id_key) ? $request->yookassa_shop_id_key : '';
            $post['yookassa_secret_key'] = !empty($request->yookassa_secret_key) ? $request->yookassa_secret_key : '';
            $post['yookassa_unfo'] = !empty($request->yookassa_unfo) ? $request->yookassa_unfo : '';
        }else{
            $post['is_yookassa_enabled']    = 'off';
        }

        // Xendit
        if ($request->is_Xendit_enabled == 'on' && !empty($request->Xendit_image)) {
            $Xendit_image = upload_theme_image($theme_id, $request->Xendit_image);
            if ($Xendit_image['status'] == false) {
                return redirect()->back()->with('error', $Xendit_image['message']);
            } else {
                $where = ['name' => 'Xendit_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['Xendit_image'] = $Xendit_image['image_path'];
            }
        }
        if($request->is_Xendit_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_Xendit_enabled'   => 'required',
                    'Xendit_api_key'      => 'required',
                    'Xendit_token_key'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_Xendit_enabled'] = !empty($request->is_Xendit_enabled) ? $request->is_Xendit_enabled : 'off';
            $post['Xendit_api_key'] = !empty($request->Xendit_api_key) ? $request->Xendit_api_key : '';
            $post['Xendit_token_key'] = !empty($request->Xendit_token_key) ? $request->Xendit_token_key : '';
            $post['Xendit_unfo'] = !empty($request->Xendit_unfo) ? $request->Xendit_unfo : '';
        }else{
            $post['is_Xendit_enabled']    = 'off';
        }

        // Midtrans
        if ($request->is_midtrans_enabled == 'on' && !empty($request->midtrans_image)) {
            $midtrans_image = upload_theme_image($theme_id, $request->midtrans_image);
            if ($midtrans_image['status'] == false) {
                return redirect()->back()->with('error', $midtrans_image['message']);
            } else {
                $where = ['name' => 'midtrans_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['midtrans_image'] = $midtrans_image['image_path'];
            }
        }
        if($request->is_midtrans_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_midtrans_enabled'   => 'required',
                    'midtrans_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_midtrans_enabled'] = !empty($request->is_midtrans_enabled) ? $request->is_midtrans_enabled : '';
            $post['midtrans_secret_key'] = !empty($request->midtrans_secret_key) ? $request->midtrans_secret_key : '';
            $post['midtrans_unfo'] = !empty($request->midtrans_unfo) ? $request->midtrans_unfo : '';
        }else{
            $post['is_midtrans_enabled']    = 'off';
        }

        // Nepalste
        if ($request->is_nepalste_enabled == 'on' && !empty($request->nepalste_image)) {
            $nepalste_image = upload_theme_image($theme_id, $request->nepalste_image);
            if ($nepalste_image['status'] == false) {
                return redirect()->back()->with('error', $nepalste_image['message']);
            } else {
                $where = ['name' => 'nepalste_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['nepalste_image'] = $nepalste_image['image_path'];
            }
        }
        if($request->is_nepalste_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_nepalste_enabled'   => 'required',
                    'nepalste_public_key'   => 'required',
                    'nepalste_secret_key'   => 'required',
                    'nepalste_mode'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['nepalste_mode'] = !empty($request->nepalste_mode) ? $request->nepalste_mode : '';
            $post['is_nepalste_enabled'] = !empty($request->is_nepalste_enabled) ? $request->is_nepalste_enabled : '';
            $post['nepalste_public_key'] = !empty($request->nepalste_public_key) ? $request->nepalste_public_key : '';
            $post['nepalste_secret_key'] = !empty($request->nepalste_secret_key) ? $request->nepalste_secret_key : '';
            $post['nepalste_unfo'] = !empty($request->nepalste_unfo) ? $request->nepalste_unfo : '';
        }else{
            $post['is_nepalste_enabled']    = 'off';
        }

        // PayHere
        if ($request->is_payhere_enabled == 'on' && !empty($request->payhere_image)) {
            $payhere_image = upload_theme_image($theme_id, $request->payhere_image);
            if ($payhere_image['status'] == false) {
                return redirect()->back()->with('error', $payhere_image['message']);
            } else {
                $where = ['name' => 'payhere_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['payhere_image'] = $payhere_image['image_path'];
            }
        }
        if($request->is_payhere_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_payhere_enabled'        => 'required',
                    'payhere_mode'              => 'required',
                    'payhere_merchant_id'       => 'required',
                    'payhere_merchant_secret'   => 'required',
                    'payhere_app_id'            => 'required',
                    'payhere_app_secret'        => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_payhere_enabled'] = !empty($request->is_payhere_enabled) ? $request->is_payhere_enabled : '';
            $post['payhere_mode'] = !empty($request->payhere_mode) ? $request->payhere_mode : '';
            $post['payhere_merchant_id'] = !empty($request->payhere_merchant_id) ? $request->payhere_merchant_id : '';
            $post['payhere_merchant_secret'] = !empty($request->payhere_merchant_secret) ? $request->payhere_merchant_secret : '';
            $post['payhere_app_id'] = !empty($request->payhere_app_id) ? $request->payhere_app_id : '';
            $post['payhere_app_secret'] = !empty($request->payhere_app_secret) ? $request->payhere_app_secret : '';
            $post['payhere_unfo'] = !empty($request->payhere_unfo) ? $request->payhere_unfo : '';
        }else{
            $post['is_payhere_enabled']    = 'off';
        }

        // Khalti
        if ($request->is_khalti_enabled == 'on' && !empty($request->khalti_image)) {
            $khalti_image = upload_theme_image($theme_id, $request->khalti_image);
            if ($khalti_image['status'] == false) {
                return redirect()->back()->with('error', $khalti_image['message']);
            } else {
                $where = ['name' => 'khalti_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['khalti_image'] = $khalti_image['image_path'];
            }
        }
        if($request->is_khalti_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_khalti_enabled'   => 'required',
                    'khalti_public_key'   => 'required',
                    'khalti_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_khalti_enabled'] = !empty($request->is_khalti_enabled) ? $request->is_khalti_enabled : '';
            $post['khalti_public_key'] = !empty($request->khalti_public_key) ? $request->khalti_public_key : '';
            $post['khalti_secret_key'] = !empty($request->khalti_secret_key) ? $request->khalti_secret_key : '';
            $post['khalti_unfo'] = !empty($request->khalti_unfo) ? $request->khalti_unfo : '';
        }else{
            $post['is_khalti_enabled']    = 'off';
        }

        // AuthorizeNet
        if ($request->is_authorizenet_enabled == 'on' && !empty($request->authorizenet_image)) {
            $authorizenet_image = upload_theme_image($theme_id, $request->authorizenet_image);
            if ($authorizenet_image['status'] == false) {
                return redirect()->back()->with('error', $authorizenet_image['message']);
            } else {
                $where = ['name' => 'authorizenet_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['authorizenet_image'] = $authorizenet_image['image_path'];
            }
        }
        if($request->is_authorizenet_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_authorizenet_enabled'   => 'required',
                    'authorizenet_mode'         => 'required',
                    'authorizenet_login_id'     => 'required',
                    'authorizenet_transaction_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_authorizenet_enabled'] = !empty($request->is_authorizenet_enabled) ? $request->is_authorizenet_enabled : '';
            $post['authorizenet_mode'] = !empty($request->authorizenet_mode) ? $request->authorizenet_mode : '';
            $post['authorizenet_login_id'] = !empty($request->authorizenet_login_id) ? $request->authorizenet_login_id : '';
            $post['authorizenet_transaction_key'] = !empty($request->authorizenet_transaction_key) ? $request->authorizenet_transaction_key : '';
            $post['authorizenet_unfo'] = !empty($request->authorizenet_unfo) ? $request->authorizenet_unfo : '';
        }else{
            $post['is_authorizenet_enabled']    = 'off';
        }


        // Tap
        if ($request->is_tap_enabled == 'on' && !empty($request->tap_image)) {
            $tap_image = upload_theme_image($theme_id, $request->tap_image);
            if ($tap_image['status'] == false) {
                return redirect()->back()->with('error', $tap_image['message']);
            } else {
                $where = ['name' => 'tap_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['tap_image'] = $tap_image['image_path'];
            }
        }
        if($request->is_tap_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_tap_enabled'   => 'required',
                    'tap_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_tap_enabled'] = !empty($request->is_tap_enabled) ? $request->is_tap_enabled : '';
            $post['tap_secret_key'] = !empty($request->tap_secret_key) ? $request->tap_secret_key : '';
            $post['tap_unfo'] = !empty($request->tap_unfo) ? $request->tap_unfo : '';
        }else{
            $post['is_tap_enabled']    = 'off';
        }

        // PhonePe
        if ($request->is_phonepe_enabled == 'on' && !empty($request->phonepe_image)) {
            $phonepe_image = upload_theme_image($theme_id, $request->phonepe_image);
            if ($phonepe_image['status'] == false) {
                return redirect()->back()->with('error', $phonepe_image['message']);
            } else {
                $where = ['name' => 'phonepe_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['phonepe_image'] = $phonepe_image['image_path'];
            }
        }
        if($request->is_phonepe_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_phonepe_enabled'        => 'required',
                    'phonepe_mode'              => 'required',
                    'phonepe_merchant_key'      => 'required',
                    'phonepe_salt_key'          => 'required',
                    'phonepe_merchant_user_id'  => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['phonepe_mode'] = !empty($request->phonepe_mode) ? $request->phonepe_mode : '';
            $post['is_phonepe_enabled'] = !empty($request->is_phonepe_enabled) ? $request->is_phonepe_enabled : '';
            $post['phonepe_merchant_key'] = !empty($request->phonepe_merchant_key) ? $request->phonepe_merchant_key : '';
            $post['phonepe_salt_key'] = !empty($request->phonepe_salt_key) ? $request->phonepe_salt_key : '';
            $post['phonepe_merchant_user_id'] = !empty($request->phonepe_merchant_user_id) ? $request->phonepe_merchant_user_id : '';
            $post['phonepe_unfo'] = !empty($request->phonepe_unfo) ? $request->phonepe_unfo : '';
        }else{
            $post['is_phonepe_enabled']    = 'off';
        }

        // Paddle
        if ($request->is_paddle_enabled == 'on' && !empty($request->paddle_image)) {
            $paddle_image = upload_theme_image($theme_id, $request->paddle_image);
            if ($paddle_image['status'] == false) {
                return redirect()->back()->with('error', $paddle_image['message']);
            } else {
                $where = ['name' => 'paddle_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paddle_image'] = $paddle_image['image_path'];
            }
        }
        if($request->is_paddle_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paddle_enabled'         => 'required',
                    'paddle_mode'               => 'required',
                    'paddle_vendor_id'          => 'required',
                    'paddle_vendor_auth_code'   => 'required',
                    'paddle_public_key'         => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['paddle_mode'] = !empty($request->paddle_mode) ? $request->paddle_mode : '';
            $post['is_paddle_enabled'] = !empty($request->is_paddle_enabled) ? $request->is_paddle_enabled : '';
            $post['paddle_vendor_id'] = !empty($request->paddle_vendor_id) ? $request->paddle_vendor_id : '';
            $post['paddle_vendor_auth_code'] = !empty($request->paddle_vendor_auth_code) ? $request->paddle_vendor_auth_code : '';
            $post['paddle_public_key'] = !empty($request->paddle_public_key) ? $request->paddle_public_key : '';
            $post['paddle_unfo'] = !empty($request->paddle_unfo) ? $request->paddle_unfo : '';
        }else{
            $post['is_paddle_enabled']    = 'off';
        }

        // Paiement Pro
        if ($request->is_paiementpro_enabled == 'on' && !empty($request->paiementpro_image)) {
            $paiementpro_image = upload_theme_image($theme_id, $request->paiementpro_image);
            if ($paiementpro_image['status'] == false) {
                return redirect()->back()->with('error', $paiementpro_image['message']);
            } else {
                $where = ['name' => 'paiementpro_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['paiementpro_image'] = $paiementpro_image['image_path'];
            }
        }
        if($request->is_paiementpro_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    // 'paiementpro_mode'          => 'required',
                    'paiementpro_merchant_id'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['paiementpro_mode'] = !empty($request->paiementpro_mode) ? $request->paiementpro_mode : '';
            $post['is_paiementpro_enabled'] = !empty($request->is_paiementpro_enabled) ? $request->is_paiementpro_enabled : '';
            $post['paiementpro_merchant_id'] = !empty($request->paiementpro_merchant_id) ? $request->paiementpro_merchant_id : '';
            $post['paiementpro_unfo'] = !empty($request->paiementpro_unfo) ? $request->paiementpro_unfo : '';
        }else{
            $post['is_paiementpro_enabled']    = 'off';
        }

        // FedPay
        if ($request->is_fedpay_enabled == 'on' && !empty($request->fedpay_image)) {
            $fedpay_image = upload_theme_image($theme_id, $request->fedpay_image);
            if ($fedpay_image['status'] == false) {
                return redirect()->back()->with('error', $fedpay_image['message']);
            } else {
                $where = ['name' => 'fedpay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['fedpay_image'] = $fedpay_image['image_path'];
            }
        }
        if($request->is_fedpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_fedpay_enabled'   => 'required',
                    'fedpay_mode'         => 'required',
                    'fedpay_public_key'   => 'required',
                    'fedpay_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['fedpay_mode'] = !empty($request->fedpay_mode) ? $request->fedpay_mode : '';
            $post['is_fedpay_enabled'] = !empty($request->is_fedpay_enabled) ? $request->is_fedpay_enabled : '';
            $post['fedpay_public_key'] = !empty($request->fedpay_public_key) ? $request->fedpay_public_key : '';
            $post['fedpay_secret_key'] = !empty($request->fedpay_secret_key) ? $request->fedpay_secret_key : '';
            $post['fedpay_unfo'] = !empty($request->fedpay_unfo) ? $request->fedpay_unfo : '';
        }else{
            $post['is_fedpay_enabled']    = 'off';
        }

        // CinetPay
        if ($request->is_cinetpay_enabled == 'on' && !empty($request->cinet_pay_image)) {
            $cinet_pay_image = upload_theme_image($theme_id, $request->cinet_pay_image);
            if ($cinet_pay_image['status'] == false) {
                return redirect()->back()->with('error', $cinet_pay_image['message']);
            } else {
                $where = ['name' => 'cinet_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['cinet_pay_image'] = $cinet_pay_image['image_path'];
            }
        }
        if($request->is_cinetpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_cinetpay_enabled'    => 'required',
                    'cinet_pay_site_id'      => 'required',
                    'cinet_pay_api_key'      => 'required',
                    'cinet_pay_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_cinetpay_enabled'] = !empty($request->is_cinetpay_enabled) ? $request->is_cinetpay_enabled : '';
            $post['cinet_pay_site_id'] = !empty($request->cinet_pay_site_id) ? $request->cinet_pay_site_id : '';
            $post['cinet_pay_api_key'] = !empty($request->cinet_pay_api_key) ? $request->cinet_pay_api_key : '';
            $post['cinet_pay_secret_key'] = !empty($request->cinet_pay_secret_key) ? $request->cinet_pay_secret_key : '';
            $post['cinet_pay_unfo'] = !empty($request->cinet_pay_unfo) ? $request->cinet_pay_unfo : '';
        }else{
            $post['is_cinetpay_enabled']    = 'off';
        }

         // Senangpay
         if ($request->is_Senangpay_enabled == 'on' && !empty($request->senang_pay_image)) {
            $senang_pay_image = upload_theme_image($theme_id, $request->senang_pay_image);
            if ($senang_pay_image['status'] == false) {
                return redirect()->back()->with('error', $senang_pay_image['message']);
            } else {
                $where = ['name' => 'senang_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['senang_pay_image'] = $senang_pay_image['image_path'];
            }
        }
        if($request->is_Senangpay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_Senangpay_enabled'    => 'required',
                    'Senangpay_mode'          => 'required',
                    'senang_pay_merchant_id'  => 'required',
                    'senang_pay_secret_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['Senangpay_mode'] = !empty($request->Senangpay_mode) ? $request->Senangpay_mode : '';
            $post['is_Senangpay_enabled'] = !empty($request->is_Senangpay_enabled) ? $request->is_Senangpay_enabled : '';
            $post['senang_pay_merchant_id'] = !empty($request->senang_pay_merchant_id) ? $request->senang_pay_merchant_id : '';
            $post['senang_pay_secret_key'] = !empty($request->senang_pay_secret_key) ? $request->senang_pay_secret_key : '';
            $post['senang_pay_unfo'] = !empty($request->senang_pay_unfo) ? $request->senang_pay_unfo : '';
        }else{
            $post['is_Senangpay_enabled']    = 'off';
        }

        // CyberSource
        if ($request->is_cybersource_enabled == 'on' && !empty($request->cybersource_pay_image)) {
            $cybersource_pay_image = upload_theme_image($theme_id, $request->cybersource_pay_image);
            if ($cybersource_pay_image['status'] == false) {
                return redirect()->back()->with('error', $cybersource_pay_image['message']);
            } else {
                $where = ['name' => 'cybersource_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['cybersource_pay_image'] = $cybersource_pay_image['image_path'];
            }
        }
        if($request->is_cybersource_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_cybersource_enabled'        => 'required',
                    'cybersource_pay_merchant_id'   => 'required',
                    'cybersource_pay_secret_key'    => 'required',
                    'cybersource_pay_api_key'       => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_cybersource_enabled'] = !empty($request->is_cybersource_enabled) ? $request->is_cybersource_enabled : '';
            $post['cybersource_pay_merchant_id'] = !empty($request->cybersource_pay_merchant_id) ? $request->cybersource_pay_merchant_id : '';
            $post['cybersource_pay_secret_key'] = !empty($request->cybersource_pay_secret_key) ? $request->cybersource_pay_secret_key : '';
            $post['cybersource_pay_api_key'] = !empty($request->cybersource_pay_api_key) ? $request->cybersource_pay_api_key : '';
            $post['cybersource_pay_unfo'] = !empty($request->cybersource_pay_unfo) ? $request->cybersource_pay_unfo : '';
        }else{
            $post['is_cybersource_enabled']    = 'off';
        }

         // ozow
         if ($request->is_ozow_enabled == 'on' && !empty($request->ozow_pay_image)) {
            $ozow_pay_image = upload_theme_image($theme_id, $request->ozow_pay_image);
            if ($ozow_pay_image['status'] == false) {
                return redirect()->back()->with('error', $ozow_pay_image['message']);
            } else {
                $where = ['name' => 'ozow_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['ozow_pay_image'] = $ozow_pay_image['image_path'];
            }
        }
        if($request->is_ozow_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_ozow_enabled'       => 'required',
                    'ozow_mode'             => 'required',
                    'ozow_pay_Site_key'     => 'required',
                    'ozow_pay_private_key'  => 'required',
                    'ozow_pay_api_key'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['ozow_mode'] = !empty($request->ozow_mode) ? $request->ozow_mode : '';
            $post['is_ozow_enabled'] = !empty($request->is_ozow_enabled) ? $request->is_ozow_enabled : '';
            $post['ozow_pay_Site_key'] = !empty($request->ozow_pay_Site_key) ? $request->ozow_pay_Site_key : '';
            $post['ozow_pay_private_key'] = !empty($request->ozow_pay_private_key) ? $request->ozow_pay_private_key : '';
            $post['ozow_pay_api_key'] = !empty($request->ozow_pay_api_key) ? $request->ozow_pay_api_key : '';
            $post['ozow_pay_unfo'] = !empty($request->ozow_pay_unfo) ? $request->ozow_pay_unfo : '';
        }else{
            $post['is_ozow_enabled']    = 'off';
        }

        // myfatoorah
        if ($request->is_myfatoorah_enabled == 'on' && !empty($request->myfatoorah_pay_image)) {
            $myfatoorah_pay_image = upload_theme_image($theme_id, $request->myfatoorah_pay_image);
            if ($myfatoorah_pay_image['status'] == false) {
                return redirect()->back()->with('error', $myfatoorah_pay_image['message']);
            } else {
                $where = ['name' => 'myfatoorah_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['myfatoorah_pay_image'] = $myfatoorah_pay_image['image_path'];
            }
        }
        if($request->is_myfatoorah_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_myfatoorah_enabled'         => 'required',
                    'myfatoorah_mode'               => 'required',
                    'myfatoorah_pay_country_iso'    => 'required',
                    'myfatoorah_pay_api_key'        => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['myfatoorah_mode'] = !empty($request->myfatoorah_mode) ? $request->myfatoorah_mode : '';
            $post['is_myfatoorah_enabled'] = !empty($request->is_myfatoorah_enabled) ? $request->is_myfatoorah_enabled : '';
            $post['myfatoorah_pay_country_iso'] = !empty($request->myfatoorah_pay_country_iso) ? $request->myfatoorah_pay_country_iso : '';
            $post['myfatoorah_pay_api_key'] = !empty($request->myfatoorah_pay_api_key) ? $request->myfatoorah_pay_api_key : '';
            $post['myfatoorah_pay_unfo'] = !empty($request->myfatoorah_pay_unfo) ? $request->myfatoorah_pay_unfo : '';
        }else{
            $post['is_myfatoorah_enabled']    = 'off';
        }

        // Easebuzz
        if ($request->is_easebuzz_enabled == 'on' && !empty($request->easebuzz_image)) {
            $easebuzz_image = upload_theme_image($theme_id, $request->easebuzz_image);
            if ($easebuzz_image['status'] == false) {
                return redirect()->back()->with('error', $easebuzz_image['message']);
            } else {
                $where = ['name' => 'easebuzz_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['easebuzz_image'] = $easebuzz_image['image_path'];
            }
        }
        if($request->is_easebuzz_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_easebuzz_enabled'       => 'required',
                    'easebuzz_merchant_key'     => 'required',
                    'easebuzz_salt_key'         => 'required',
                    'easebuzz_enviroment_name'  => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_easebuzz_enabled'] = !empty($request->is_easebuzz_enabled) ? $request->is_easebuzz_enabled : '';
            $post['easebuzz_merchant_key'] = !empty($request->easebuzz_merchant_key) ? $request->easebuzz_merchant_key : '';
            $post['easebuzz_salt_key'] = !empty($request->easebuzz_salt_key) ? $request->easebuzz_salt_key : '';
            $post['easebuzz_enviroment_name'] = !empty($request->easebuzz_enviroment_name) ? $request->easebuzz_enviroment_name : '';
            $post['easebuzz_unfo'] = !empty($request->easebuzz_unfo) ? $request->easebuzz_unfo : '';
        }else{
            $post['is_easebuzz_enabled']    = 'off';
        }

        // NMI
        if ($request->is_nmi_enabled == 'on' && !empty($request->nmi_image)) {
            $nmi_image = upload_theme_image($theme_id, $request->nmi_image);
            if ($nmi_image['status'] == false) {
                return redirect()->back()->with('error', $nmi_image['message']);
            } else {
                $where = ['name' => 'nmi_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['nmi_image'] = $nmi_image['image_path'];
            }
        }
        if($request->is_nmi_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_nmi_enabled'        => 'required',
                    'nmi_api_private_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_nmi_enabled'] = !empty($request->is_nmi_enabled) ? $request->is_nmi_enabled : '';
            $post['nmi_api_private_key'] = !empty($request->nmi_api_private_key) ? $request->nmi_api_private_key : '';
            $post['nmi_unfo'] = !empty($request->nmi_unfo) ? $request->nmi_unfo : '';
        }else{
            $post['is_nmi_enabled']    = 'off';
        }

        //PayU
        if ($request->is_payu_enabled == 'on' && !empty($request->payu_image)) {
            $payu_image = upload_theme_image($theme_id, $request->payu_image);
            if ($payu_image['status'] == false) {
                return redirect()->back()->with('error', $payu_image['message']);
            } else {
                $where = ['name' => 'payu_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['payu_image'] = $payu_image['image_path'];
            }
        }

        if($request->is_payu_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_payu_enabled'        => 'required',
                    'payu_merchant_key'   => 'required',
                    'payu_salt_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_payu_enabled'] = !empty($request->is_payu_enabled) ? $request->is_payu_enabled : 'off';
            $post['payu_merchant_key'] = !empty($request->payu_merchant_key) ? $request->payu_merchant_key : '';
            $post['payu_salt_key'] = !empty($request->payu_salt_key) ? $request->payu_salt_key : '';
            $post['payu_mode'] = !empty($request->payu_mode) ? $request->payu_mode : '';
            $post['payu_unfo'] = !empty($request->payu_unfo) ? $request->payu_unfo : '';
        }else{
            $post['is_payu_enabled']    = 'off';
        }


        //Paynow
        if ($request->is_paynow_enabled == 'on' && !empty($request->paynow_pay_image)) {
            $paynow_pay_image = upload_theme_image($theme_id, $request->paynow_pay_image);
            if ($paynow_pay_image['status'] == false) {
                return redirect()->back()->with('error', $paynow_pay_image['message']);
            } else {
                $where = ['name' => 'paynow_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                 $post['paynow_pay_image'] = $paynow_pay_image['image_path'];
            }
        }

        if($request->is_paynow_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_paynow_enabled'        => 'required',
                    'paynow_mode'   => 'required',
                    'paynow_pay_integration_id'   => 'required',
                    'paynow_pay_integration_key'   => 'required',
                    'paynow_pay_merchant_email'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['paynow_mode'] = !empty($request->paynow_mode) ? $request->paynow_mode : '';
            $post['is_paynow_enabled'] = !empty($request->is_paynow_enabled) ? $request->is_paynow_enabled : '';
            $post['paynow_pay_integration_id'] = !empty($request->paynow_pay_integration_id) ? $request->paynow_pay_integration_id : '';
            $post['paynow_pay_integration_key'] = !empty($request->paynow_pay_integration_key) ? $request->paynow_pay_integration_key : '';
            $post['paynow_pay_merchant_email'] = !empty($request->paynow_pay_merchant_email) ? $request->paynow_pay_merchant_email : '';
            $post['paynow_pay_unfo'] = !empty($request->paynow_pay_unfo) ? $request->paynow_pay_unfo : '';
        }else{
            $post['is_paynow_enabled']    = 'off';
        }



        // Sofort
        if ($request->is_sofort_enabled == 'on' && !empty($request->sofort_image)) {
            $sofort_image = upload_theme_image($theme_id, $request->sofort_image);
            if ($sofort_image['status'] == false) {
                return redirect()->back()->with('error', $sofort_image['message']);
            } else {
                $where = ['name' => 'sofort_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['sofort_image'] = $sofort_image['image_path'];
            }
        }
        if($request->is_sofort_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_sofort_enabled'        => 'required',
                    'sofort_publishable_key'   => 'required',
                    'sofort_secret_key'        => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_sofort_enabled']          = !empty($request->is_sofort_enabled) ? $request->is_sofort_enabled : 'off';
            $post['sofort_publishable_key']     = !empty($request->sofort_publishable_key) ? $request->sofort_publishable_key : '';
            $post['sofort_secret_key']          = !empty($request->sofort_secret_key) ? $request->sofort_secret_key : '';
            $post['sofort_unfo']                = !empty($request->sofort_unfo) ? $request->sofort_unfo : '';
        }else{
            $post['is_sofort_enabled']    = 'off';
        }

        // ESewa
        if ($request->is_esewa_enabled == 'on' && !empty($request->esewa_image)) {
            $esewa_image = upload_theme_image($theme_id, $request->esewa_image);
            if ($esewa_image['status'] == false) {
                return redirect()->back()->with('error', $esewa_image['message']);
            } else {
                $where = ['name' => 'esewa_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();
                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['esewa_image'] = $esewa_image['image_path'];
            }
        }
        if($request->is_esewa_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_esewa_enabled'     => 'required',
                    'esewa_merchant_key'   => 'required',
                    'esewa_mode'           => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_esewa_enabled']          = !empty($request->is_esewa_enabled) ? $request->is_esewa_enabled : 'off';
            $post['esewa_merchant_key']     = !empty($request->esewa_merchant_key) ? $request->esewa_merchant_key : '';
            $post['esewa_mode']          = !empty($request->esewa_mode) ? $request->esewa_mode : '';
            $post['esewa_unfo']                = !empty($request->esewa_unfo) ? $request->esewa_unfo : '';
        }else{
            $post['is_esewa_enabled']    = 'off';
        }

        // dpo
        if ($request->is_dpopay_enabled == 'on' && ! empty($request->dpo_pay_image)) {
            $dpo_pay_image = upload_theme_image($theme_id, $request->dpo_pay_image);
            if ($dpo_pay_image['status'] == false) {
                return redirect()->back()->with('error', $dpo_pay_image['message']);
            } else {
                $where = ['name' => 'dpo_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (! empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['dpo_pay_image'] = $dpo_pay_image['image_path'];
            }
        }
        if($request->is_dpopay_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_dpopay_enabled'      => 'required',
                    'dpo_pay_Company_Token'  => 'required',
                    'dpo_pay_Service_Type'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_dpopay_enabled'] = ! empty($request->is_dpopay_enabled) ? $request->is_dpopay_enabled : '';
            $post['dpo_pay_Company_Token'] = ! empty($request->dpo_pay_Company_Token) ? $request->dpo_pay_Company_Token : '';
            $post['dpo_pay_Service_Type'] = ! empty($request->dpo_pay_Service_Type) ? $request->dpo_pay_Service_Type : '';
            $post['dpo_pay_unfo'] = ! empty($request->dpo_pay_unfo) ? $request->dpo_pay_unfo : '';
        }else{
            $post['is_dpopay_enabled']    = 'off';
        }


        // Braintree
        if ($request->is_braintree_enabled == 'on' && !empty($request->braintree_pay_image)) {
            $braintree_pay_image = upload_theme_image($theme_id, $request->braintree_pay_image);
            if ($braintree_pay_image['status'] == false) {
                return redirect()->back()->with('error', $braintree_pay_image['message']);
            } else {
                $where = ['name' => 'braintree_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['braintree_pay_image'] = $braintree_pay_image['image_path'];
            }
        }
        $post['braintree_mode'] = !empty($request->braintree_mode) ? $request->braintree_mode : '';
        $post['is_braintree_enabled'] = !empty($request->is_braintree_enabled) ? $request->is_braintree_enabled : '';
        $post['braintree_pay_merchant_id'] = !empty($request->braintree_pay_merchant_id) ? $request->braintree_pay_merchant_id : '';
        $post['braintree_pay_public_key'] = !empty($request->braintree_pay_public_key) ? $request->braintree_pay_public_key : '';
        $post['braintree_pay_private_key'] = !empty($request->braintree_pay_private_key) ? $request->braintree_pay_private_key : '';
        $post['braintree_pay_unfo'] = !empty($request->braintree_pay_unfo) ? $request->braintree_pay_unfo : '';


        // PowerTranz
        if ($request->is_powertranz_enabled == 'on' && !empty($request->powertranz_pay_image)) {
            $powertranz_pay_image = upload_theme_image($theme_id, $request->powertranz_pay_image);
            if ($powertranz_pay_image['status'] == false) {
                return redirect()->back()->with('error', $powertranz_pay_image['message']);
            } else {
                $where = ['name' => 'powertranz_pay_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(storage_path($Setting->value))) {
                        File::delete(storage_path($Setting->value));
                    }
                }
                $post['powertranz_pay_image'] = $powertranz_pay_image['image_path'];
            }
        }
        $post['powertranz_mode'] = !empty($request->powertranz_mode) ? $request->powertranz_mode : '';
        $post['is_powertranz_enabled'] = !empty($request->is_powertranz_enabled) ? $request->is_powertranz_enabled : '';
        $post['powertranz_pay_merchant_id'] = !empty($request->powertranz_pay_merchant_id) ? $request->powertranz_pay_merchant_id : '';
        $post['powertranz_pay_processing_password'] = !empty($request->powertranz_pay_processing_password) ? $request->powertranz_pay_processing_password : '';
        $post['powertranz_pay_production_url'] = !empty($request->powertranz_pay_production_url) ? $request->powertranz_pay_production_url : '';
        $post['powertranz_pay_unfo'] = !empty($request->powertranz_pay_unfo) ? $request->powertranz_pay_unfo : '';


        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore()
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

    public function CookieSettings(Request $request)
    {
        session()->put(['setting_tab' => 'cookie_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $validator = \Validator::make(
            $request->all(),
            [
                'cookie_title'                  => 'required',
                'cookie_description'            => 'required',
                'strictly_cookie_title'         => 'required',
                'strictly_cookie_description'   => 'required',
                'more_information_title'        => 'required',
                'contactus_url'                 => 'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);

        $post['enable_cookie'] = isset($request->enable_cookie) ? 'on' : 'off';
        $post['cookie_logging'] = isset($request->cookie_logging) ? 'on' : 'off';

        if ($post['enable_cookie'] == 'on') {
            $post['cookie_title']                   = $request->cookie_title;
            $post['cookie_description']             = $request->cookie_description;
            $post['strictly_cookie_title']          = $request->strictly_cookie_title;
            $post['strictly_cookie_description']    = $request->strictly_cookie_description;
            $post['more_information_title']         = $request->more_information_title;
            $post['contactus_url']                  = $request->contactus_url;
        }
        $settings = Utility::cookies();
        $settingQuery = Setting::query();
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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function CookieConsent(Request $request)
    {
        $settings = Utility::Seting();
        if (isset($settings['enable_cookie']) && isset($settings['cookie_logging']) && $settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
            $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            // Generate new CSV line
            $browser_name = $whichbrowser->browser->name ?? null;
            $os_name = $whichbrowser->os->name ?? null;
            $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $device_type = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);

            // $ip = $_SERVER['REMOTE_ADDR'];
            $ip = '192.168.29.182';
            $date = (new \DateTime())->format('Y-m-d');
            $time = (new \DateTime())->format('H:i:s') . ' UTC';

            $new_line = implode(',', [
                $ip,
                $date,
                $time,
                json_encode($request['cookie']),
                $device_type,
                $browser_language,
                $browser_name,
                $os_name,
                isset($query['country']) ? $query['country'] : '-',
                isset($query['region']) ? $query['region'] : '-',
                isset($query['regionName']) ? $query['regionName'] : '-',
                isset($query['city']) ? $query['city'] : '-',
                isset($query['zip']) ? $query['zip'] : '-',
                isset($query['lat']) ? $query['lat'] : '-',
                isset($query['lon']) ? $query['lon'] : '-'
            ]);

            if (!file_exists(storage_path() . '/uploads/sample/cookie_data.csv')) {
                $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                file_put_contents(storage_path() . '/uploads/sample/cookie_data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            file_put_contents(storage_path() . '/uploads/sample/cookie_data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);

            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function RecaptchaSetting(Request $request)
    {
        session()->put(['setting_tab' => 'recaptcha_setting']);
        if (\Auth::user()->type == 'super admin') {
            $user = Auth::user();
            $rules = [];
            $rules['google_recaptcha_key']      = 'required|string|max:50';
            $rules['google_recaptcha_secret']   = 'required|string|max:50';
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }
            $data_recaptcha = [
                'RECAPTCHA_MODULE'  => (isset($request->recaptcha_module)  ? 'yes' : 'no') ?? 'no',
                'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
                'NOCAPTCHA_SECRET'  => $request->google_recaptcha_secret,
                'NOCAPTCHA_VERSON'  => $request->google_recaptcha_version ?? 'v2',
            ];

            $settingQuery = Setting::query();
            foreach ($data_recaptcha as $key => $data) {
                $status = (clone $settingQuery)->updateOrCreate(
                    [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                    [
                        'value'         => $data,
                        'name'          => $key,
                        'theme_id'      => APP_THEME(),
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            }

            if (isset($status)) {
                return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function ChatgptSettings(Request $request) {
        session()->put(['setting_tab' => 'chatgpt_setting']);
        if (auth()->user()->type == 'super admin') {
            $key_arr = $request->api_key;
            foreach ($key_arr as  $data)
            {
                if($data !='' && !empty($data))
                {
                    ApikeySetiings::updateOrCreate([
                        'key' => $data,
                        'created_by' => auth()->user()->id
                    ]);
                }
            }

            ApikeySetiings::whereNotIn('key',$key_arr)->delete();

            if (!empty($request->chatgpt_key)) {
                $post = [];
                $post['chatgpt_key'] = $request->chatgpt_key;
                unset($post['_token']);
                $settingQuery = Setting::query();
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
                            'store_id'      => getCurrentStore(auth()->user()->id, null),
                            'created_by'    => auth()->user()->id,
                        ]
                    );
                }
            }
            return redirect()->back()->with('success', __('Chatgpykey successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function CustomizeSetting(Request $request)
    {
        session()->put(['setting_tab' => 'style_setting']);
        $post = $request->all();
        unset($post['_token']);
        $settingQuery = Setting::query();
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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Customize Css successfully updated.');
    }

    public function LoyalityProgramSettings(Request $request)
    {
        session()->put(['setting_tab' => 'loyality_pro_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : APP_THEME();

        $loyality_program_enabled = !empty($request->loyality_program_enabled) ? $request->loyality_program_enabled : 'off';
        $reward_point = !empty($request->reward_point) ? $request->reward_point : 0;

        $post['loyality_program_enabled'] = $loyality_program_enabled;
        $post['reward_point'] = $reward_point;
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            $status = (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        if (isset($status)) {
            return redirect()->back()->with('success', __('Settings updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }

    }

    public function WoocommerceSettings(Request $request)
    {
        session()->put(['setting_tab' => 'woocom_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : '';

        $post['woocommerce_setting_enabled'] = $request->woocommerce_setting_enabled;
        if (isset($request->woocommerce_setting_enabled) && $request->woocommerce_setting_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'woocommerce_store_url' => 'required|string',
                    'woocommerce_consumer_key' => 'required|string',
                    'woocommerce_consumer_secret' => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
        }


        $post['woocommerce_store_url'] = $request->woocommerce_store_url;
        $post['woocommerce_consumer_key'] = $request->woocommerce_consumer_key;
        $post['woocommerce_consumer_secret'] = $request->woocommerce_consumer_secret;
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            $status = (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        if (isset($status)) {
            return redirect()->back()->with('success', __('Woocommerce setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }

    }

    public function shopifySettings(Request $request)
    {
        session()->put(['setting_tab' => 'shopify_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : '';

        $post['shopify_setting_enabled'] = $request->shopify_setting_enabled;
        if (isset($request->shopify_setting_enabled) && $request->shopify_setting_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'shopify_store_url' => 'required|string',
                    'shopify_access_token' => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
        }


        $post['shopify_store_url'] = $request->shopify_store_url;
        $post['shopify_access_token'] = $request->shopify_access_token;
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            $status = (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        if (isset($status)) {
            return redirect()->back()->with('success', __('Shopify setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function SystemSettings(Request $request)
    {
        session()->put(['setting_tab' => 'system_setting']);
        // Get the authenticated user
        $user = auth()->user();

        // Get the theme ID and directory
        $theme_id = APP_THEME();
        $default_language = $request->has('default_language') ? $request->default_language : 'en';
        if (auth()->user()->type == 'super admin') {
            $user = auth()->user();
            $user->default_language = $default_language;
            $user->save();

            $store = Store::where('id', $user->current_store)->first();
            $store->default_language = $default_language;
            $store->save();

        } else {
            $user = auth()->user();
            $user->default_language = $default_language;
            $user->save();

            $store = Store::where('id', $user->current_store)->first();
            $store->default_language = $default_language;
            $store->save();


        }
        if (!empty($request->currency_format) || !empty($request->defult_currancy) || !empty($request->default_language) || !empty($request->site_currency_symbol_position) || !empty($request->site_date_format) || !empty($request->site_time_format)) {
            $post = $request->all();
            unset($post['_token']);
            $settingQuery = Setting::query();
            foreach ($post as $key => $data) {
                $status = (clone $settingQuery)->updateOrCreate(
                    [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                    [
                        'value'         => $data,
                        'name'          => $key,
                        'theme_id'      => APP_THEME(),
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            }

            if (isset($status)) {
                return redirect()->back()->with('success', __('System setting successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        }
    }

    public function customMassage(Request $request, $slug=null)
    {
        session()->put(['setting_tab' => 'whatsapp_msg_setting']);
        $validator = \Validator::make(
            $request->all(), [
                'whatsapp_content' => 'required',
                'whatsapp_item_variable' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $post['whatsapp_item_variable'] = $request->whatsapp_item_variable;
        $post['whatsapp_content'] = $request->whatsapp_content;

        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {

            $status = (clone $settingQuery)->updateOrCreate(
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
                    'created_by'    => Auth::user()->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );

        }
        if (isset($status)) {
            return redirect()->back()->with('success', __('Whatsapp setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function StockSettings(Request $request)
    {
        session()->put(['setting_tab' => 'stock_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : '';
        $validator = \Validator::make(
            $request->all(),
            [
                'low_stock_threshold' => 'required',
                'out_of_stock_threshold' => 'required',
                'notification' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $post['low_stock_threshold'] = $request->low_stock_threshold;
        $post['out_of_stock_threshold'] = $request->out_of_stock_threshold;
        $post['stock_management'] = $request->has('stock_management') ? $request->stock_management : 'off';
        $post['notification'] =  json_encode($request->input('notification'));
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            $status = (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
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
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }
        if (isset($status)) {
            return redirect()->back()->with('success', __('Stock setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function WhatsappSettings(Request $request)
    {
        session()->put(['setting_tab' => 'whatsapp_setting']);
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : '';

        $post['whatsapp_setting_enabled'] = $request->whatsapp_setting_enabled;
        if (isset($request->whatsapp_setting_enabled) && $request->whatsapp_setting_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'whatsapp_contact_number' => ['required', 'regex:/^\+[1-9]\d{1,14}$/'],
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
        }


        $post['whatsapp_contact_number'] = $request->whatsapp_contact_number;

        if ($request->whatsapp_setting_enabled == 'off') {
            $post['whatsapp_contact_number'] = '';
        }

        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            $status = (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }
        return redirect()->back()->with('success', 'Whatsapp setting successfully updated.');
    }

    public function whatsapp_notification(Request $request)
    {
        session()->put(['setting_tab' => 'whatsapp_notify_setting']);
        $usr = auth()->user();

        if ($usr->type == 'super admin' || $usr->type == 'admin') {
            $WhatsappMessage  = WhatsappMessage::where('user_id', $usr->id)->where('id', $request->notification_id)->first();
            $WhatsappMessage->is_active = $request->status;
            $WhatsappMessage->save();

            return response()->json(['success' => 'WhatsappNotification change successfully.']);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function whatsapp_notification_setting(Request $request)
    {
        session()->put(['setting_tab' => 'whatsapp_notify_setting']);
        $theme_id = !empty( APP_THEME()) ?  APP_THEME() : '';

        $validator = \Validator::make(
            $request->all(),
            [
                'whatsapp_phone_number_id' => 'required|string',
                'whatsapp_access_token' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $post['whatsapp_phone_number_id'] = $request->whatsapp_phone_number_id;
        $post['whatsapp_access_token'] = $request->whatsapp_access_token;
        $settingQuery = Setting::query();
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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }
        return redirect()->back()->with('success', 'Whatsapp Business API setting successfully updated.');
    }

    public function Testwhatsappmassage(Request $request)
    {
        $email_setting = $request->all();
        $settings = Setting::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('value', 'name')->toArray();

        $user = auth()->user();

        $data = [];
        $data['whatsapp_phone_number_id'] = $request->whatsapp_phone_number_id;
        $data['whatsapp_access_token'] = $request->whatsapp_access_token;


        return view('setting.test_whatsappmessage', compact('email_setting', 'settings', 'data'));
    }

    public function testSendwhatsappmassage(Request $request)
    {
        session()->put(['setting_tab' => 'whatsapp_notify_setting']);
        $validator = \Validator::make(
            $request->all(),
            [
                'mobile' => 'required',
                'whatsapp_phone_number_id' => 'required',
                'whatsapp_access_token' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        try {

            $url = 'https://graph.facebook.com/v17.0/' . $request->whatsapp_phone_number_id . '/messages';

            $data = array(
                'messaging_product' => 'whatsapp',
                'to' => $request->mobile,
                'type' => 'template',
                'template' => array(
                    'name' => 'hello_world',
                    'language' => array(
                        'code' => 'en_US'
                    )
                )
            );

            $headers = array(
                'Authorization: Bearer ' . $request->whatsapp_access_token,
                'Content-Type: application/json'
            );

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);

            $responseData = json_decode($response);

            curl_close($ch);

            if (isset($responseData->error)) {

                return redirect()->back()->with('error', $responseData->error->message);
            } else {
                return redirect()->back()->with('successs', 'Massage send Successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function TwilioSettings(Request $request)
    {
        session()->put(['setting_tab' => 'twilio_setting']);
        $theme_id = !empty(App_THEME()) ? APP_THEME() : '';
        $post['twilio_setting_enabled'] = $request->twilio_setting_enabled;
        if (isset($request->twilio_setting_enabled) && $request->twilio_setting_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'twilio_sid' => 'required|string',
                    'twilio_token' => 'required|string',
                    'twilio_from' => 'required|numeric',
                    'twilio_notification_number' => 'required|numeric',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
        }


        $post['twilio_sid'] =$request->twilio_sid;
        $post['twilio_token'] =$request->twilio_token;
        $post['twilio_from'] = $request->twilio_from;
        $post['twilio_notification_number'] = $request->twilio_notification_number;

        if ($request->twilio_setting_enabled == 'off') {
            $post['twilio_sid'] = '';
            $post['twilio_token'] = '';
            $post['twilio_from'] = '';
            $post['twilio_notification_number'] = '';
        }

        $settingQuery = Setting::query();
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
                    'store_id'      => getCurrentStore(auth()->user()->id, null),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }
        return redirect()->back()->with('success', 'Twilio setting successfully updated.');
    }

    public function currencySettings(Request $request)
    {
        session()->put(['setting_tab' => 'currency_setting']);
        $post = $request->all();
        unset($post['_token']);
        unset($post['_method']);
        if (isset($post['defult_currancy'])) {
            $data = explode('-', $post['defult_currancy']);
            $post['defult_currancy_symbol'] = $data[0];
            $post['CURRENCY'] = $data[0];
            $post['defult_currancy']        = $data[1];
            $post['CURRENCY_NAME']        = $data[1];
        } else {
            $post['defult_currancy']        = 'USD';
            $post['defult_currancy_symbol'] = '$';
            $post['CURRENCY_NAME']        = 'USD';
            $post['CURRENCY'] = '$';
        }
        if (isset($post['site_currency_symbol_position'])) {
            $post['site_currency_symbol_position'] = !empty($request->site_currency_symbol_position) ? $request->site_currency_symbol_position : 'pre';
        }
        $settingQuery = Setting::query();
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

        return redirect()->back()->with('success', __('Currency Setting save successfully.'));
    }

    public function updateNoteValue(Request $request)
    {
        $symbol_position = 'pre';
        $symbol = '$';
        $format = '1';
        $price  = '10000';
        $number = explode('.', $price);
        $length = strlen(trim($number[0]));
        $currency_symbol = explode('-',$request->defult_currancy);

        if ($length > 3) {

            $decimal_separator  = (isset($request->decimal_separator) && $request->decimal_separator == 'dot') ? '.' : ',';
            $thousand_separator = (isset($request->thousand_separator) && $request->thousand_separator == 'dot') ? '.' : ',';
        } else {
            $decimal_separator  = (isset($request->decimal_separator) &&  $request->decimal_separator == 'dot')  ? '.' : ',';
            $thousand_separator = (isset($request->thousand_separator) &&  $request->thousand_separator == 'dot') ? '.' : ',';
        }

        if (isset($request->site_currency_symbol_position) && $request->site_currency_symbol_position == "post") {
            $symbol_position = 'post';
        }

        if (isset($request->defult_currancy)) {
            $symbol = $request->defult_currancy;
        }

        if (isset($request->currency_format)) {
            $format = $request->currency_format;
        }
        if (isset($request->currency_space)) {
            $currency_space = isset($request->currency_space) ? $request->currency_space : '';
        }
        if (isset($request->site_currency_symbol_name)) {
            $symbol = $request->site_currency_symbol_name == 'symbol' ? $currency_symbol[0] : $currency_symbol[1];
        }
        $formatted_price = (
            ($symbol_position == "pre")  ?  $symbol : '') . (isset($currency_space) && $currency_space == 'withspace' ? ' ' : '')
            . number_format($price, $format, $decimal_separator, $thousand_separator) . (isset($currency_space) && $currency_space == 'withspace' ? ' ' : '') .
            (($symbol_position == "post") ?  $symbol : '');
        return response()->json(['success' => true,'formatted_price' => $formatted_price]);
    }

    public function getEmailSettingFields(Request $request)
    {
        if (auth()->user()->type == 'super admin') {
            $setting = getSuperAdminAllSetting();
            $folder = 'SuperAdmin';
        } else {
            $setting = getAdminAllSetting();
        }
       $email_setting = $request->emailsetting;
       $returnHTML = view('setting.email_fields', compact('email_setting','setting'))->render();
       $response = [
           'is_success' => true,
           'message' => '',
           'html' => $returnHTML,
       ];
       return response()->json($response);
    }

    public function settingForm(Request $request) {
        try {
            $user = auth()->user();
            $timezones = config('timezones');
            $setting = ($user->type == 'super admin') ? getSuperAdminAllSetting() : getAdminAllSetting();

            // Define the mapping of tab types to view names
            $views = [
                'email_setting' => 'setting.email_setting',
                'brand_setting' => 'setting.brand_setting',
                'system_setting' => 'setting.system_setting',
                'storage_setting' => 'setting.storage_setting',
                'payment_setting' => 'setting.payment_setting',
                'cookie_setting' => 'setting.cookie_setting',
                'recaptcha_setting' => 'setting.recaptcha_setting',
                'cache_setting' => 'setting.cache_setting',
                'style_setting' => 'setting.style_setting',
                'chat_gpt_setting' => 'setting.chat_gpt_setting',
                'currency_setting' => 'setting.currency_setting',
                'email_notify_setting' => 'setting.email_notify_setting',
                'loyality_pro_setting' => 'setting.loyality_pro_setting',
                'shopify_setting' => 'setting.shopify_setting',
                'stock_setting' => 'setting.stock_setting',
                'twilio_setting' => 'setting.twilio_setting',
                'whatsapp_notify_setting' => 'setting.whatsapp_notify_setting',
                'whatsapp_setting' => 'setting.whatsapp_setting',
                'woocom_setting' => 'setting.woocom_setting',
                'webhook_setting' => 'setting.webhook_setting',
                'pwa_setting' => 'setting.pwa_setting',
                'pixel_field_setting' => 'setting.pixel_field_setting',
                'tax_opt_setting' => 'setting.tax_opt_setting',
                'whatsapp_msg_setting' => 'setting.whatsapp_msg_setting',
                'tax_opt_setting' => 'setting.tax_opt_setting',
                'refund_setting' => 'setting.refund_setting',
                'seo_setting' => 'setting.seo_setting'
            ];

            // Default tab type to 'email_setting' if not provided
            $tabType = $request->tab_type ?? 'email_setting';

            $plan = Plan::find($user->plan_id);
            $store_settings = Store::where('id', getCurrentStore())->first();





            if ($tabType == 'email_setting') {
                $email_setting = EmailTemplate::$email_settings;
                $get_setting = $setting['email_setting'] ?? 'smtp';
                $view = view($views[$tabType], compact('get_setting', 'email_setting', 'setting'));
            } elseif ($tabType == 'storage_setting') {
                $file_type = config('files_types');
                $view = view($views[$tabType], compact('setting', 'file_type'));
            }  elseif ($tabType == 'pwa_setting') {
                try {
                    $pwa_data = \File::get(storage_path('uploads/customer_app/store_' . $store_settings->id . '/manifest.json'));
                    $pwa_data = json_decode($pwa_data);
                } catch (\Throwable $th) {
                    $pwa_data = '';
                }
                $view = view($views[$tabType], compact('setting', 'pwa_data', 'store_settings'));
            }  elseif ($tabType == 'pixel_field_setting') {
                $PixelFields = PixelFields::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
                $view = view($views[$tabType], compact('setting', 'PixelFields'));
            } elseif ($tabType == 'webhook_setting') {
                $webhooks = Webhook::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
                $view = view($views[$tabType], compact('setting', 'webhooks'));
            } elseif ($tabType == 'chatgpt_setting') {
                $ai_key_settings = ApikeySetiings::get();
                $view = view($views[$tabType], compact('setting', 'ai_key_settings'));
            } elseif ($tabType == 'tax_opt_setting') {
                // $emailTemplates = EmailTemplate::all();
                $taxes = Tax::where('theme_id', APP_THEME())
                    ->where('store_id', getCurrentStore())
                    ->pluck('name', 'id')
                    ->prepend('Shipping tax based on cart items', '');

                $tax_option = TaxOption::where('created_by',$user->id)
                    ->where('store_id',getCurrentStore())
                    ->where('theme_id',APP_THEME())
                    ->pluck('value', 'name')->toArray();
                $view = view($views[$tabType], compact('setting', 'tax_option', 'taxes'));
            } elseif ($tabType == 'whatsapp_notify_setting') {
                $WhatsappNotification = WhatsappMessage::where('theme_id',APP_THEME())
                ->where('store_id', getCurrentStore())
                ->get();
                $view = view($views[$tabType], compact('setting', 'WhatsappNotification'));
            } elseif ($tabType == 'email_notify_setting') {
                $s_admin = User::where('type', 'super admin')->first();
                if ($s_admin) {
                    $emailTemplates = EmailTemplate::where('created_by',$s_admin->id)->get();
                } else {
                    $emailTemplates = EmailTemplate::whereNot('created_by',\Auth::user()->id)->get();
                }
                $view = view($views[$tabType], compact('emailTemplates', 'setting'));
            } else {
                $view = view($views[$tabType] ?? $views['email_setting'], compact('setting', 'timezones','plan'));
            }

            // Render the HTML view
            $html = $view->render();

            // Return JSON response
            return response()->json([
                'is_success' => true,
                "msg" => ucfirst(str_replace('_', ' ', $tabType)) . " form get successfully.",
                "data" => ['content' => $html]
            ]);
        } catch (\Exception $e) {
             // Return JSON response
             return response()->json([
                'is_success' => false,
                "msg" => __('Something went wrong!'),
                "data" => ['content' => null]
            ]);
        }
    }

    public function SEOSetting(Request $request)
    {
        session()->put(['setting_tab' => 'seo_setting']);
        $post = $request->all();
        unset($post['_token']);
        $dir        = Storage::url('uploads');
        if ($request->hasFile('metaimage')) {
            $fileName = rand(10,100).'_'.time() . "_" . $request->metaimage->getClientOriginalName();
            $path = Utility::upload_file($request,'metaimage',$fileName,$dir,[]);
        }
        if (!empty($request->metaimage) && isset($path['url'])) {
            $image = str_replace('/storage', '', $path['url']);
        }
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            if ($key == 'metaimage' && isset($path['url'])) {
                (clone $settingQuery)->updateOrCreate(
                    [
                        'name' => 'metaimage',
                        'theme_id' => APP_THEME(),
                        'store_id' => getCurrentStore()
                    ],
                    [
                        'value'         => $path['url'] ?? null,
                        'name'          => 'metaimage',
                        'theme_id'      => APP_THEME(),
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            } else {
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
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'SEO successfully updated.');
    }
}
