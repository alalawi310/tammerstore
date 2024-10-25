<?php

namespace App\Http\Middleware;

use App\Models\{Plan, User};
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Session;

class SetLocate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(file_exists(storage_path() . "/installed")) {
            $lang = Session::get('LANGUAGE');
            if (empty($lang)) {
                $lang =  Cookie::get('LANGUAGE') ?? null;
            }            

            if(auth()->check())
            {
                if (auth()->user() && auth()->user()->language) {
                    $lang = auth()->user()->language;
                } elseif (empty($lang)) {
                    $lang = Cookie::get('CURRENT_LANGUAGE');
                    if (empty($lang)) {
                        $lang = auth()->user()->language;
                    }
                }
            } else {
                if (!Session::has('LANGUAGE') || empty($lang)) {
                    $superadmin = User::where('type','super admin')->first();
                    $lang = !empty($superadmin->language) ? $superadmin->language : null;
                }
                
            }

            if (empty($lang)) {
                $lang = 'en';
            }

            if(auth()->user())
            {
                $user = auth()->user();
                if($user->type == 'admin')
                {
                    // if($plan)
                    // {
                        $datetime1 = new \DateTime($user->plan_expire_date);
                        $datetime2 = new \DateTime(date('Y-m-d'));

                        $interval = $datetime2->diff($datetime1);
                        $days     = $interval->format('%r%a');

                        if($days <= 0)
                        {
                            if (!isset($user->plan_id) || empty($user->plan_id)) {
                                $plan = Plan::where('duration','Unlimited')->first();
                                if ($plan) {
                                    $user->assignPlan($plan->id);
                                }
                            }

                            // return redirect()->route('plan.index')->with('error', __('Your Plan is expired.'));
                        }
                        if($user->trial_expire_date != null)
                        {
                            if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                            {
                                $user->assignPlan(1);
                                // return redirect()->route('plan.index')->with('error', __('Your Trial plan Expired.'));
                                // return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                            }
                        }
                    // }
                }
            }

            App::setLocale($lang);

            // Check the current route name
            $currentRouteName = $request->route()->getName();

            // Forget the session variable if not on the settings page
            if ($currentRouteName !== 'setting.index') {
                session()->forget('setting_tab');
            }
            if (!in_array($currentRouteName, ['app-setting.index', 'mobilescreen.content'])) {
                session()->forget('app_setting_tab');
            }
            if ($currentRouteName !== 'countries.index') {
                session()->forget('country_active_tab');
            }
        }

        return $next($request);
    }
}
