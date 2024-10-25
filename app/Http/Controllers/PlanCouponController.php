<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PlanCoupon;
use App\Models\Plan;
use App\Models\PlanUserCoupon;
use App\Models\Utility;
use Illuminate\Validation\Rule;

class PlanCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user() && auth()->user()->isAbleTo('Manage Coupon'))
        {
            $coupons = PlanCoupon::get();

            return view('plan-coupon.index', compact('coupons'));
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
        if (auth()->user() && auth()->user()->isAbleTo('Create Coupon'))
        {
            return view('plan-coupon.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        if (auth()->user() && auth()->user()->isAbleTo('Create Coupon'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'name' => 'required|unique:plan_coupons,name',
                                   'discount' => [
                                        'required',
                                        'numeric',
                                        function ($attribute, $value, $fail) {
                                            if ($value >= 100) {
                                                $fail('The ' . $attribute . ' must be below 100.');
                                            }
                                        },
                                    ],
                                   'limit' => 'required|numeric',
                                   'code' => 'required|unique:plan_coupons,code',
                                   ]
                                );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $plancoupon           = new PlanCoupon();
            $plancoupon->name     = $request->name;
            $plancoupon->discount = $request->discount;
            $plancoupon->limit    = $request->limit;
            $plancoupon->code     = strtoupper($request->code);
            $plancoupon->save();

            return redirect()->back()->with('success', __('Coupon successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $planCoupon = PlanCoupon::find($id);
        if ($planCoupon) {
            $userCoupons = PlanUserCoupon::where('coupon_id', $planCoupon->id)->get();
            return view('plan-coupon.show', compact('planCoupon', 'userCoupons'));
        } else {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanCoupon $planCoupon)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Edit Coupon'))
        {
            return view('plan-coupon.edit', compact('planCoupon'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  PlanCoupon $planCoupon)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Edit Coupon'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'name' => [
                                                'required',
                                                Rule::unique('plan_coupons')->ignore($planCoupon->id),
                                            ],
                                   'discount' => [
                                        'required',
                                        'numeric',
                                        function ($attribute, $value, $fail) {
                                            if ($value >= 100) {
                                                $fail('The ' . $attribute . ' must be below 100.');
                                            }
                                        },
                                    ],
                                   'limit' => 'required|numeric',
                                   'code' =>  [
                                                'required',
                                                Rule::unique('plan_coupons')->ignore($planCoupon->id),
                                            ],
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $plancoupon           = PlanCoupon::find($planCoupon->id);
            $plancoupon->name     = $request->name;
            $plancoupon->discount = $request->discount;
            $plancoupon->limit    = $request->limit;
            $plancoupon->code     = $request->code;

            $plancoupon->save();

            return redirect()->back()->with('success', __('Coupon successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanCoupon $planCoupon)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Delete Coupon'))
        {
            $planCoupon->delete();

            return redirect()->back()->with('success', __('Coupon successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyCoupon(Request $request)
    {
        $plan = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
        if($plan && $request->coupon != '')
        {
            $original_price = self::formatPrice($plan->price);
            $coupons        = PlanCoupon::whereRaw('BINARY `code` = ?', [$request->coupon])->where('is_active', '1')->first();

            if(!empty($coupons))
            {
                $usedCoupun = $coupons->used_coupon();
                if($coupons->limit == $usedCoupun)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price),
                            'message' => __('This coupon code has expired.'),
                        ]
                    );
                }
                else
                {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $plan_price     = $plan->price - $discount_value;
                    $price          = self::formatPrice($plan->price - $discount_value);
                    $discount_value = '-' . self::formatPrice($discount_value);

                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'price' => number_format($plan_price, 2),
                            'message' => __('Coupon code has applied successfully.'),
                        ]
                    );
                }
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, 2),
                        'message' => __('This coupon code is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price){
        $currency = Utility::GetValueByName('CURRENCY');
        return $currency . number_format($price, 2);
    }
}
