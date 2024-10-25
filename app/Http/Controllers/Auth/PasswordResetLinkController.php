<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Notifications\CustomResetPasswordNotification;
use App\Models\User;

class PasswordResetLinkController extends Controller
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
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $settings = \App\Models\Utility::Seting();
        
        if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
            // Validate captcha
            $captchaResponse = $request->input('g-recaptcha-response');
            if (empty($captchaResponse)) {
                return redirect()->back()->with('status', __('Please checked RECAPTCHA.'));
            }
            $captchaSecretKey = $settings['NOCAPTCHA_SECRET'] ?? null;
            $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $captchaSecretKey,
                'response' => $captchaResponse,
            ]);
            $captchaResult = json_decode($response->body());

            if (!$captchaResult->success) {
                return redirect()->back()->with('status', __('RECAPTCHA Captcha validation failed.'));
            }
        }

        try {
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
    
            $request->validate([
                'email' => ['required', 'email'],
            ],[
                'email.required' => __('Invalid Email address'),
                'email.email' => __('Invalid Email address'),
            ]);
            $user = User::where('email', $request->email)->first();
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email'),
                function ($user, $token) {
                    
                    $user->notify(new CustomResetPasswordNotification($token));
                }
            );
    
            if ($status == 'passwords.throttled') {
                return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __('We have already send password reset link on your email address.')]);
            } else {
                return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __('Invalid email address.')]);
            }
            
        } catch (\Throwable $e) {
            return redirect()->back()->withInput($request->only('email'))
                    ->withErrors(['email' => $e->getMessage()]);
        }
    }
}
