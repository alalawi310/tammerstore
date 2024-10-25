<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Events\CreateCustomer;
use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Models\PageOption;
use App\Models\Store;
use App\Models\Student;
use App\Models\User;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Newsletter;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Qirolab\Theme\Theme;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Cookie;

class CustomerLoginController extends Controller
{

    private function validator(Request $request, $slug)
    {
        $store    = Store::where('slug', $slug)->first();
        $themeId = $store->theme_id;
        $storeId = $store->id;
        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];
        $validate = Validator::make(
            $request->all(),
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'min:5',
                    'max:191',
                    function ($attribute, $value, $fail) use ($themeId, $storeId) {
                        $customerExists = \DB::table('customers')
                            ->where('email', $value)
                            ->where('theme_id', $themeId)
                            ->where('store_id', $storeId)
                            ->exists();

                        if (!$customerExists) {
                            $fail("These credentials do not match our records.");
                        }
                    },
                ],
                'password' => [
                    'required',
                    'string',
                    'min:4',
                    'max:255',
                ],
            ]
        );

        $vali     = Customer::where('email', $request->email)->where('theme_id', $store->theme_id)->where('store_id', $store->id)->count();
        if ($validate->fails()) {

            $message = $validate->getMessageBag();
            return false;
        } elseif ($vali > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function showLoginForm($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $theme_id = $store->theme_id;
        $currentTheme = GetCurrenctTheme($slug);
        Theme::set($currentTheme);

        $data = getThemeSections($currentTheme, $slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = get_nav_menu((array) $section->header->section->menu_type->menu_ids ??
            []) ?? [];
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $languages = Utility::languages();

        $currency = Utility::GetValueByName('CURRENCY_NAME',$store->theme_id, $store->id);

        if (Utility::CustomerAuthCheck($slug) == true) {
            return redirect()->route('landing_page', $slug);
        } else {
            return view('front_end.Auth.login', compact('store', 'slug', 'currentTheme', 'currantLang', 'currency', 'languages', 'section', 'topNavItems') + $data + $sqlData);
        }
    }

    public function login(Request $request, $slug, $cart = 0)
    {
        if ($this->validator($request, $slug) === true) {
            $store = Store::where(['slug' => $slug])->first();

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (!is_null($store)) {
                $credentials['store_id'] = $store->id;
            } else {
                $credentials['store_id'] = 0;
            }

            // Retrieve the user based on the email address
            $user = Customer::where('email', $credentials['email'])->where('store_id',$credentials['store_id'])->first();

            try {
                // If a user with the given email exists and the password matches using Bcrypt
                if ($user && Hash::check($credentials['password'], $user->password)) {
                    auth()->guard('customers')->login($user, $request->filled('remember'));

                    if (module_is_active('ProductAffiliate')) {
                        do {
                            $code = rand(100000, 999999);
                        } while (Customer::where('affiliate_code', $code)->exists());

                        if (empty($user->affiliate_code)) {
                            $user->update(['affiliate_code' => $code]);
                        }
                    }
                    $request->merge([
                        'customer_id' => auth('customers')->user()->id,
                        'store_id' => $store->id,
                        'slug' => $store->slug,
                        'theme_id' => $store->theme_id
                    ]);

                    $response = Cart::cart_list_cookie($request->all(), $store->id);
                    $response = json_decode(json_encode($response));

                    if (!empty($response->data->cart_total_product)) {
                        foreach ($response->data->product_list as $item) {
                            $final_price = $item->final_price;
                            $product_id = $item->product_id;
                            $variant_id = !empty($item->variant_id) ? $item->variant_id : 0;
                            $qty = $item->qty;

                            $cart = Cart::where('customer_id', $request->customer_id)
                                        ->where('product_id', $product_id)
                                        ->where('variant_id', $variant_id)
                                        ->where('theme_id', $request->theme_id)
                                        ->where('store_id', $store->id)
                                        ->first();

                            if (empty($cart)) {
                                $cart = new Cart();
                                $cart->qty = $qty;
                            } else {
                                $cart->qty += $qty;
                                $final_price += $cart->price;
                            }

                            $cart->customer_id = $request->customer_id;
                            $cart->product_id = $product_id;
                            $cart->variant_id = $variant_id;
                            $cart->price = $final_price;
                            $cart->theme_id = $request->theme_id;
                            $cart->store_id = $store->id;
                            $cart->save();

                        }
                    }
                    $cart_array = [];
                    $cart_json = json_encode($cart_array);
                    Cookie::queue('cart', $cart_json, 1440);

                    return redirect()->route('landing_page', $slug);
                } else {
                    return redirect()->back()->with('error', __('These credentials do not match our records.'));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->back()->with('error', __('These credentials do not match our records.'));
            }
        } else {
            return redirect()->back()->with('error', __('These credentials do not match our records.'));
        }
    }

    public function logout($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }

        Auth::guard('customers')->logout();

        return redirect()->route('landing_page', $slug);
    }

    public function register($slug, $ref = null)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $languages = Utility::languages();
        $currency = Utility::GetValueByName('CURRENCY_NAME',$store->theme_id, $store->id);

        $currentTheme = GetCurrenctTheme($slug);
        Theme::set($currentTheme);

        $data = getThemeSections($currentTheme, $slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = get_nav_menu((array) $section->header->section->menu_type->menu_ids ??
            []) ?? [];

        if($ref == '')
        {
            $ref = 0;
        }
        if (module_is_active('ProductAffiliate')) {
            $refCode = Customer::where('affiliate_code' , '=', $ref)->first();
            if(isset($refCode) && $refCode->affiliate_code != $ref)
            {
                return redirect()->route('customer.register',$slug);
            }
        }
        return view('front_end.Auth.register', compact('store', 'currentTheme', 'currantLang', 'languages', 'currency', 'section', 'topNavItems','ref') + $data + $sqlData);
    }

    protected function registerData($storeSlug, Request $request)
    {
        $store = Store::where('slug', $storeSlug)->first();
        $themeId = $store->theme_id;
        $storeId = $store->id;
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $validate = \Validator::make(
            $request->all(),
            [
                'first_name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) use ($themeId, $storeId) {
                        $customerExists = \DB::table('customers')
                            ->where('email', $value)
                            ->where('theme_id', $themeId)
                            ->where('store_id', $storeId)
                            ->exists();

                        if ($customerExists) {
                            $fail("This Email already exist, please login.");
                        }
                    },
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ]
        );
        if ($validate->fails()) {
            $messages = $validate->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if (module_is_active('ProductAffiliate')) {
            do {
                $code = rand(100000, 999999);
            } while (Customer::where('affiliate_code', $code)->exists());
        }
        $customer = new Customer();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->password = Hash::make($request->password);
        $customer->type = 'customer';
        $customer->profile_image = 'avatar.png';
        if (module_is_active('ProductAffiliate')) {
            $customer->affiliate_code = $code;
            $customer->used_affiliate_code = $request->ref_code ?? '';
        }
        $customer->regiester_date = date('Y-m-d');
        $customer->last_active = date('Y-m-d');
        $customer->store_id = $store->id;
        $customer->theme_id = $store->theme_id;
        $customer->save();

        if($request->subscribe == 'on')
        {
            try{
                $newsletters=new Newsletter();
                $newsletters->customer_id=$customer->id;
                $newsletters->email = $request->email;
                $newsletters->store_id = $store->id;
                $newsletters->theme_id = $store->theme_id;
                $newsletters->save();
            }
            catch(\Exception $e)
            {
                return redirect()->back()->with('error', __($e));
            }
        }

        $slug = $storeSlug;
        $email = $request->email;
        $password = $request->password;
        $store_id = $store->id;
        event(new CreateCustomer($customer));

        if (Auth::guard('customers')->attempt($request->only(['email' => $email, 'password' => $password, 'store_id' => $store_id]), $request->filled('remember'))) {
            $cart = session()->get($store->slug);
            //Authentication passed...

            return redirect()->route('customer.login',$slug);
        }

        return redirect()->route('customer.login',$slug);
    }

    public function forgotPasswordForm($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $theme_id = $store->theme_id;
        $currentTheme = GetCurrenctTheme($slug);
        Theme::set($currentTheme);

        $data = getThemeSections($currentTheme, $slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = get_nav_menu((array) $section->header->section->menu_type->menu_ids ??
            []) ?? [];
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $languages = Utility::languages();

        $currency = Utility::GetValueByName('CURRENCY_NAME',$store->theme_id, $store->id);

        if (Utility::CustomerAuthCheck($slug) == true) {
            return redirect()->route('landing_page', $slug);
        } else {
            return view('front_end.Auth.forgot-password', compact('store', 'slug', 'currentTheme', 'currantLang', 'currency', 'languages', 'section', 'topNavItems') + $data + $sqlData);
        }
    }

    public function forgotPassword(Request $request, $storeSlug)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $settings = \App\Models\Utility::Seting();
            config(
                [
                    'mail.driver' => $settings['MAIL_DRIVER'],
                    'mail.host' => $settings['MAIL_HOST'],
                    'mail.port' => $settings['MAIL_PORT'],
                    'mail.encryption' => $settings['MAIL_ENCRYPTION'],
                    'mail.username' => $settings['MAIL_USERNAME'],
                    'mail.password' => $settings['MAIL_PASSWORD'],
                    'mail.from.address' => $settings['MAIL_FROM_ADDRESS'],
                    'mail.from.name' => $settings['MAIL_FROM_NAME'],
                ]
            );


            $store = Store::where('slug', $storeSlug)->first();
            $customer = Customer::where('email', $request->email)->where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();

            if (!$customer) {
                return redirect()->back()->withErrors(['email' => 'No account found with that email address.']);
            }
            $customer->storeSlug = $storeSlug; // Set the storeSlug property
            $broker = Password::broker('customers');

            // This will generate a token and send a reset link to the customer's email
            $response = $broker->sendResetLink(
                ['email' => $request->email],
                function ($user, $token) use ($customer) {
                    $customer->sendPasswordResetNotification($token);
                }
            );

            if ($response == Password::RESET_LINK_SENT) {
                return redirect()->back()->with(['status' => 'A password reset link has been sent to your email address.']);
            } else {
                return redirect()->back()->withErrors(['email' => 'Unable to send password reset link. Please try again.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Unable to send password reset link. Please try again.']);
        }
    }

    public function resetPasswordForm($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $theme_id = $store->theme_id;
        $currentTheme = GetCurrenctTheme($slug);
        Theme::set($currentTheme);

        $data = getThemeSections($currentTheme, $slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = get_nav_menu((array) $section->header->section->menu_type->menu_ids ??
            []) ?? [];
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $languages = Utility::languages();

        $currency = Utility::GetValueByName('CURRENCY_NAME',$store->theme_id, $store->id);

        if (Utility::CustomerAuthCheck($slug) == true) {
            return redirect()->route('landing_page', $slug);
        } else {
            return view('front_end.Auth.reset-password', compact('store', 'slug', 'currentTheme', 'currantLang', 'currency', 'languages', 'section', 'topNavItems') + $data + $sqlData);
        }
    }

    public function resetPassword(Request $request, $storeSlug)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $store = Store::where('slug', $storeSlug)->first();
        // Check if the user with the provided email exists in the customers table
        $customer = Customer::where('email', $request->email)->where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();

        if (!$customer) {
            // User not found, handle the error accordingly (e.g., show an error message)
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('customer.login', $storeSlug)->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
