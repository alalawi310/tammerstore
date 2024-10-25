<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;
use App\Models\User;
use App\Models\DeliveryBoy;
use App\Traits\ApiResponser;

class CustomAuth
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if ($token) {
            // Extract the token part from the Authorization header
            $token = substr($token, 7);
            // Extract the user ID and token from the token string
            $token = explode('|', $token);
            $tokenId = $token[0] ?? null;
            // Check if the token exists in the personal_access_tokens table
            if ($tokenId) {
                $tokenExists = \DB::table('personal_access_tokens')
                ->where('id', $tokenId)
                ->first();
                if ($tokenExists) {
                    if ($tokenExists->tokenable_type == 'App\Models\Customer') {
                        $customer = Customer::find($tokenExists->tokenable_id);
                        auth('customers')->login($customer);
                    } elseif ($tokenExists->tokenable_type == 'App\Models\User') {
                        $user = User::find($tokenExists->tokenable_id);
                        auth()->login($user);
                    } elseif ($tokenExists->tokenable_type == 'App\Models\DeliveryBoy') {
                        $user = DeliveryBoy::find($tokenExists->tokenable_id);
                        auth('deliveryboy')->login($user);
                    }

                } elseif ($tokenId && !$tokenExists) {
                    return $this->error(['message' => __('Unauthenticated.')], __('Unauthenticated.'), 401 );
                }
            }

        }

        return $next($request);
    }
}
