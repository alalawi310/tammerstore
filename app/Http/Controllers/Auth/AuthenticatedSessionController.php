<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use App\Events\VerifyReCaptchaToken;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    public function __construct(Request $request)
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $settings = \App\Models\Utility::Seting();
        
        $validation = [];
        if(isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes')
        {
            if ($settings['NOCAPTCHA_VERSON'] == 'v2') {
                $validation['g-recaptcha-response'] = 'required';
            } elseif ($settings['NOCAPTCHA_VERSON'] == 'v3') {
                $result = event(new VerifyReCaptchaToken($request));

                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]); // Set the key to null

                    $validation['g-recaptcha-response'] = 'required';
                }
            }
        }
        $this->validate($request, $validation);
        

        $user = User::where('email',$request->email)->first();
        if($user != null)
        {
            $companyUser = User::where('id' , $user->created_by)->first();
        }

        if($user != null && $user->is_active == 0 && $user->type != 'super admin')
        {
            return redirect()->back()->with('status', __('Your Account is de-activate,please contact your Administrator.'));
        }

        if(($user != null && $user->is_enable_login == 0 || (isset($companyUser) && $companyUser != null) && $companyUser->is_enable_login == 0)  && $user->type != 'super admin')
        {
            return redirect()->back()->with('status', __('Your Account is disable,please contact your Administrator.'));
        }

        $request->authenticate();
        $request->session()->regenerate();
        if(isset($settings['email_verification']) && $settings['email_verification'] == "on")
        {
            if($user != null && $user->type == 'admin' && empty($user->email_verified_at))
            {
                return redirect()->route('verify-email')->with('status', __('Your email is not verified,please verfiy email then you can access dashboard.'));
            }
        }
        \App\Models\Utility::addNewData();
        if (module_is_active('GoogleAuthentication') && isset($user->google2fa_enable) && !empty($user->google2fa_enable)) {
            return redirect()->route('2fa.dashboard');
        } elseif (Module::has('Duo2FA') && Module::isEnabled('Duo2FA')) {
            return redirect()->route('duo2fa.form');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear the language cookie
        Cookie::queue(Cookie::forget('LANGUAGE'));
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showCustomerLoginForm($lang = '')
    {

        return view('auth.customer_login', compact('lang'));
    }
}
