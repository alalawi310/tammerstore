<?php

namespace App\Http\Controllers;

use App\Exports\CouponExport;
use App\Models\Coupon;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ShopifyConection;
use App\Models\WoocommerceConection;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user() && auth()->user()->isAbleTo('Manage Coupon')) {
            $coupons = Coupon::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();

            return view('coupon.index', compact('coupons'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->toArray();
        $category = MainCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->toArray();

        return view('coupon.create', compact('product', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Create Coupon')) {
            if ($request->coupon_type !== 'percentage' && isset($request->minimum_spend)) {
                $discountAmount = $request->discount_amount;
                $minimumSpend = $request->minimum_spend;
                $maxSpend = $request->maximum_spend;
                if ($discountAmount >= $minimumSpend) {
                    return redirect()->back()->with('error', __('Discount amount is bigger than Minimum spend'));
                } elseif ($maxSpend <= $discountAmount) {
                    return redirect()->back()->with('error', __('Discount amount is bigger than Maximum spend'));
                }
            }

            if ($request->coupon_type == 'percentage') {
                $validator = \Validator::make($request->all(), [
                    'discount_amount' => 'required|numeric|min:1|max:100',
                ]);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
            }

            $validator = \Validator::make(
                $request->all(),
                [
                    'coupon_name' => 'required|unique:coupons,coupon_name',
                    'coupon_type' => 'required',
                    'discount_amount' => 'required',
                    'coupon_limit' => 'required',
                    'coupon_expiry_date' => 'required',
                    'coupon_code' => 'required|unique:coupons,coupon_code',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $coupon = new Coupon();
            $coupon->coupon_name = $request->coupon_name;
            $coupon->coupon_type = $request->coupon_type;
            if ($request->coupon_type == 'fixed product discount') {
                $coupon->applied_product = implode(',', ! empty($request->applied_product) ? (array) $request->applied_product : []);
                $coupon->exclude_product = implode(',', ! empty($request->exclude_product) ? (array) $request->exclude_product : []);

                $coupon->applied_categories = implode(',', ! empty($request->applied_categories) ? (array) $request->applied_categories : []);
                $coupon->exclude_categories = implode(',', ! empty($request->exclude_categories) ? (array) $request->exclude_categories : []);
            }
            $coupon->minimum_spend = $request->minimum_spend;
            $coupon->maximum_spend = $request->maximum_spend;
            $coupon->discount_amount = $request->discount_amount;
            $coupon->coupon_limit = $request->coupon_limit;
            $coupon->coupon_limit_user = $request->coupon_limit_user;
            $coupon->coupon_limit_x_item = $request->coupon_limit_x_item;
            $coupon->coupon_expiry_date = $request->coupon_expiry_date;
            $coupon->coupon_code = trim($request->coupon_code);
            $coupon->status = $request->status;
            $coupon->sale_items = $request->sale_items;
            $coupon->free_shipping_coupon = $request->free_shipping_coupon;
            $coupon->theme_id = APP_THEME();
            $coupon->store_id = getCurrentStore();
            $coupon->save();

            return redirect()->back()->with('success', __('Coupon successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Create Coupon')) {
            $UserCoupons = UserCoupon::where('coupon_id', $coupon->id)->get();

            return view('coupon.show', compact('UserCoupons'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $product = Product::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->toArray();
        $category = MainCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->toArray();
        $applied_product = explode(',', $coupon->applied_product);
        $exclude_product = explode(',', $coupon->exclude_product);
        $applied_categories = explode(',', $coupon->applied_categories);
        $exclude_categories = explode(',', $coupon->exclude_categories);

        return view('coupon.edit', compact('coupon', 'product', 'category', 'applied_product', 'exclude_product', 'applied_categories', 'exclude_categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {

        if ($request->coupon_type !== 'percentage' && isset($request->minimum_spend)) {
            $discountAmount = $request->discount_amount;
            $minimumSpend = $request->minimum_spend;
            $maxSpend = $request->maximum_spend;
            if ($discountAmount >= $minimumSpend) {
                return redirect()->back()->with('error', __('Discount amount is bigger than Minimum spend'));
            } elseif ($maxSpend <= $discountAmount) {
                return redirect()->back()->with('error', __('Discount amount is bigger than Maximum spend'));
            }
        }

        if ($request->coupon_type == 'percentage') {
            $validator = \Validator::make($request->all(), [
                'discount_amount' => 'required|numeric|min:1|max:100',
            ]);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
        }
        if (auth()->user() && auth()->user()->isAbleTo('Edit Coupon')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'coupon_name' => [
                        'required',
                        Rule::unique('coupons')->ignore($coupon->id),
                    ],
                    'discount_amount' => 'required',
                    'coupon_limit' => 'required',
                    'coupon_expiry_date' => 'required',
                    'coupon_code' => [
                        'required',
                        Rule::unique('coupons')->ignore($coupon->id),
                    ],
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $coupon->coupon_name = $request->coupon_name;
            $coupon->coupon_type = $request->coupon_type;

            if ($request->coupon_type == 'fixed product discount') {
                $coupon->applied_product = implode(',', ! empty($request->applied_product) ? (array) $request->applied_product : []);
                $coupon->exclude_product = implode(',', ! empty($request->exclude_product) ? (array) $request->exclude_product : []);

                $coupon->applied_categories = implode(',', ! empty($request->applied_categories) ? (array) $request->applied_categories : []);
                $coupon->exclude_categories = implode(',', ! empty($request->exclude_categories) ? (array) $request->exclude_categories : []);
            }
            $coupon->minimum_spend = $request->minimum_spend;
            $coupon->maximum_spend = $request->maximum_spend;
            $coupon->discount_amount = $request->discount_amount;
            $coupon->coupon_limit = $request->coupon_limit;
            $coupon->coupon_expiry_date = $request->coupon_expiry_date;
            $coupon->coupon_limit_user = $request->coupon_limit_user;
            $coupon->coupon_limit_x_item = $request->coupon_limit_x_item;
            $coupon->coupon_code = trim($request->coupon_code);
            $coupon->status = $request->status;
            $coupon->sale_items = $request->sale_items;
            $coupon->free_shipping_coupon = $request->free_shipping_coupon;
            $coupon->save();

            return redirect()->back()->with('success', __('Coupon successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        if (auth()->user() && auth()->user()->isAbleTo('Delete Coupon')) {
            WoocommerceConection::where('module', 'coupon')->where('original_id', $coupon->id)->delete();

            ShopifyConection::where('module', 'coupon')->where('original_id', $coupon->id)->delete();

            $coupon->delete();

            return redirect()->back()->with('success', __('Coupon delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileExport()
    {
        $fileName = 'Coupon.xlsx';

        return Excel::download(new CouponExport, $fileName);
    }
}
