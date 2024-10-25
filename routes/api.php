<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('{slug}/register', [AuthController::class, 'register'])->middleware(['APILog']);
Route::post('{slug}/login', [AuthController::class, 'login'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-send-otp', [AuthController::class, 'forgot_password_send_otp'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-verify-otp', [AuthController::class, 'forgot_password_verify_otp'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-save', [AuthController::class, 'forgot_password_save'])->middleware(['APILog']);
Route::post('{slug}/', [ApiController::class, 'base_url'])->middleware(['APILog']);
Route::post('{slug}/logout', [AuthController::class, 'logout'])->middleware(['custom.auth', 'APILog']);

Route::post('{slug}/landingpage', [ApiController::class, 'landingpage'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/product_banner', [ApiController::class, 'product_banner'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/category', [ApiController::class, 'category'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/category-list', [ApiController::class, 'main_category'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/search', [ApiController::class, 'search'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/search-guest', [ApiController::class, 'search'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/apply-coupon', [ApiController::class, 'apply_coupon'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/categorys-product', [ApiController::class, 'categorys_product'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/categorys-product-guest', [ApiController::class, 'categorys_product_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/product-detail', [ApiController::class, 'product_detail'])->middleware(['APILog', 'custom.auth']);
Route::post('{slug}/product-detail-guest', [ApiController::class, 'product_detail_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/product-rating', [ApiController::class, 'product_rating'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/random_review', [ApiController::class, 'random_review'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/add-cart', [ApiController::class, 'addtocart'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/cart-qty', [ApiController::class, 'cart_qty'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/cart-list', [ApiController::class, 'cart_list'])->middleware(['APILog', 'custom.auth']);
Route::post('{slug}/cart-check', [ApiController::class, 'cart_check'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/cart-check-guest', [ApiController::class, 'cart_check_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/wishlist', [ApiController::class, 'wishlist'])->middleware(['APILog', 'custom.auth']);
Route::post('{slug}/wishlist-list', [ApiController::class, 'wishlist_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/bestseller', [ApiController::class, 'bestseller'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/bestseller-guest', [ApiController::class, 'bestseller_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/tranding-category', [ApiController::class, 'tranding_category'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/tranding-category-product', [ApiController::class, 'tranding_category_product'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/tranding-category-product-guest', [ApiController::class, 'tranding_category_product_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/home-category', [ApiController::class, 'home_category'])->middleware(['custom.auth','APILog']); // Main category
Route::post('{slug}/sub-category', [ApiController::class, 'sub_category'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/sub-category-guest', [ApiController::class, 'sub_category_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/featured-products', [ApiController::class, 'featured_products'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/featured-products-guest', [ApiController::class, 'featured_products'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/check-variant-stock', [ApiController::class, 'check_variant_stock'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/delivery-list', [ApiController::class, 'delivery_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/shipping', [ApiController::class, 'delivery_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/delivery-charge', [ApiController::class, 'delivery_charge'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/payment-list', [ApiController::class, 'payment_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/country-list', [ApiController::class, 'country_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/state-list', [ApiController::class, 'state_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/city-list', [ApiController::class, 'city_list'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/profile-update', [ApiController::class, 'profile_update'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/change-password', [ApiController::class, 'change_password'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/change-address', [ApiController::class, 'change_address'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/user-detail', [ApiController::class, 'user_detail'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/add-address', [ApiController::class, 'add_address'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/address-list', [ApiController::class, 'address_list'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/delete-address', [ApiController::class, 'delete_address'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/update-address', [ApiController::class, 'update_address'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/update-user-image', [ApiController::class, 'update_user_image'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/confirm-order', [ApiController::class, 'confirm_order'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/place-order', [ApiController::class, 'place_order'])->name('place-order')->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/place-order-guest', [ApiController::class, 'place_order_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/order-list', [ApiController::class, 'order_list'])->middleware([ 'custom.auth','APILog']);
Route::post('{slug}/return-order-list', [ApiController::class, 'return_order_list'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/order-detail', [ApiController::class, 'order_detail'])->middleware([ 'custom.auth','APILog']);
Route::post('{slug}/order-status-change', [ApiController::class, 'order_status_change'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/product-return', [ApiController::class, 'product_return'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/navigation', [ApiController::class, 'navigation'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/tax-guest', [ApiController::class, 'tax_guest'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/extra-url', [ApiController::class, 'extra_url'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/loyality-program-json', [ApiController::class, 'loyality_program_json'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/loyality-reward', [ApiController::class, 'loyality_reward'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/notify_user', [ApiController::class, 'notify_user'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/recent-product', [ApiController::class, 'recent_product'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/recent-product-guest', [ApiController::class, 'recent_product'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/releted-product', [ApiController::class, 'releted_product'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/releted-product-guest', [ApiController::class, 'releted_product'])->middleware(['custom.auth','APILog']);

Route::post('{slug}/random-product', [ApiController::class, 'random_product'])->middleware(['custom.auth','APILog']);

Route::post('{slug}/payment-sheet', [ApiController::class, 'payment_sheet'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/user-delete', [ApiController::class, 'user_delete'])->middleware(['custom.auth', 'APILog']);
Route::post('{slug}/subscribe', [ApiController::class, 'subscribe'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/discount-products', [ApiController::class, 'discountProducts'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/add-review', [ApiController::class, 'add_review'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/order-save', [ApiController::class, 'ordersave'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/order-cancel', [DashboardController::class, 'orderCancel'])->middleware(['custom.auth','APILog']);
Route::post('{slug}/variant-list', [ApiController::class, 'variant_list'])->middleware(['custom.auth','APILog']);

Route::prefix('admin')->as('admin.')->group(function(){
    Route::post('adminlogin', [DashboardController::class, 'login'])->middleware(['AdminApiLog']);
    Route::post('base_url', [DashboardController::class, 'base_url'])->middleware(['AdminApiLog']);
    Route::post('currency', [DashboardController::class, 'currency'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('dashboard', [DashboardController::class, 'dashboard'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('categorylist', [DashboardController::class, 'CategoryList'])->middleware(['AdminApiLog']);
    Route::get('productlist', [DashboardController::class, 'ProductList'])->middleware(['AdminApiLog']);
    Route::get('variantlist', [DashboardController::class, 'VariantList'])->middleware(['AdminApiLog']);
    Route::get('orderlist', [DashboardController::class, 'OrderList'])->middleware(['AdminApiLog']);
    Route::get('couponlist', [DashboardController::class, 'CouponList'])->middleware(['AdminApiLog']);
    Route::get('shippinglist', [DashboardController::class, 'ShippingList'])->middleware(['AdminApiLog']);
    Route::get('taxlist', [DashboardController::class, 'TaxList'])->middleware(['AdminApiLog']);

    Route::post('addcategory', [DashboardController::class, 'AddCategory'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatecategory', [DashboardController::class, 'UpdateCategory'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deletecategory', [DashboardController::class, 'DeleteCategory'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('addcoupon', [DashboardController::class, 'AddCoupon'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatecoupon', [DashboardController::class, 'UpdateCoupon'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deletecoupon', [DashboardController::class, 'DeleteCoupon'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('generatecode', [DashboardController::class, 'GenerateCode'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('addshipping', [DashboardController::class, 'AddShipping'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updateshipping', [DashboardController::class, 'UpdateShipping'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deleteshipping', [DashboardController::class, 'DeleteShipping'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('addtax', [DashboardController::class, 'AddTax'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatetax', [DashboardController::class, 'UpdateTax'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deletetax', [DashboardController::class, 'DeleteTax'])->middleware(['custom.auth','AdminApiLog']);
    
    Route::post('addvariant', [DashboardController::class, 'AddVariant'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatevariant', [DashboardController::class, 'UpdateVariant'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deletevariant', [DashboardController::class, 'DeleteVariant'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('vieworder', [DashboardController::class, 'ViewOrder'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deleteorder', [DashboardController::class, 'DeleteOrder'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('createproduct', [DashboardController::class, 'CreateProduct'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('addproduct', [DashboardController::class, 'AddProduct'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('viewproduct', [DashboardController::class, 'ViewProduct'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updateproduct', [DashboardController::class, 'UpdateProduct'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deleteproduct', [DashboardController::class, 'DeleteProduct'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('searchdata', [DashboardController::class, 'SearchData'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('createreview', [DashboardController::class, 'CreateReview'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('categoryproduct', [DashboardController::class, 'CategoryProduct'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('addreview', [DashboardController::class, 'AddReview'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deletereview', [DashboardController::class, 'DeleteReview'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatereview', [DashboardController::class, 'UpdateReview'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('reviewstatus', [DashboardController::class, 'ReviewStatus'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('productdropdown', [DashboardController::class, 'ProductDropdown'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('searchproduct', [DashboardController::class, 'SearchProduct'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('reviewlist', [DashboardController::class, 'ReviewList'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('staticsdata', [DashboardController::class, 'StaticsData'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('editprofile', [DashboardController::class, 'EditProfile'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updatepassword', [DashboardController::class, 'UpdatePassword'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('emailsetting', [DashboardController::class, 'EmailSetting'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('getemailsetting', [DashboardController::class, 'GetEmailSetting'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('loyalitysetting', [DashboardController::class, 'LoyalitySetting'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('getloyalitysetting', [DashboardController::class, 'GetLoyalitySetting'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('cod-payment', [DashboardController::class, 'CodPayment'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('get-cod-payment', [DashboardController::class, 'GetCodPayment'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('bank-payment', [DashboardController::class, 'BankPayment'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('get-bank-payment', [DashboardController::class, 'GetBankPayment'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('stripe-payment', [DashboardController::class, 'StripePayment'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('get-stripe-payment', [DashboardController::class, 'GetStripePayment'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('testmail', [DashboardController::class, 'TestMail'])->middleware(['custom.auth','AdminApiLog']);

    Route::post('changestore', [DashboardController::class, 'ChangeStore'])->middleware(['custom.auth','AdminApiLog']);

    Route::get('view-used-coupon', [DashboardController::class, 'ViewUsedCoupon'])->middleware(['custom.auth','AdminApiLog']);

    Route::delete('deletestore', [DashboardController::class, 'DeleteStore'])->middleware(['custom.auth','AdminApiLog']);

    //delivery boy
    Route::post('delivery-login', [DashboardController::class, 'deliveryLogin'])->middleware(['AdminApiLog']);
    Route::get('delivery-order-list', [DashboardController::class, 'DeliveryBoyOrderList'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('order-detail', [DashboardController::class, 'orderDetail'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('status-change', [DashboardController::class, 'statusChange'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('Home', [DashboardController::class, 'deliveryHome'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('delivery-transaction', [DashboardController::class, 'deliveryTransaction'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('change-profile', [DashboardController::class, 'changeProfile'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('logOut', [DashboardController::class, 'logout'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('delete-user', [DashboardController::class, 'delete_user'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('order-cancel', [DashboardController::class, 'orderCancel'])->middleware(['custom.auth','AdminApiLog']);

    //product-brand
    Route::post('addproductbrand', [DashboardController::class, 'AddProductBrand'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updateproductbrand', [DashboardController::class, 'UpdateProductBrand'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deleteproductbrand', [DashboardController::class, 'DeleteProductBrand'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('product-brand-status', [DashboardController::class, 'ChangeStatus'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('product-brand-popular', [DashboardController::class, 'ChangePopular'])->middleware(['custom.auth','AdminApiLog']);

    //product lable
    Route::post('addproductlable', [DashboardController::class, 'AddProductLable'])->middleware(['custom.auth','AdminApiLog']);
    Route::post('updateproductlable', [DashboardController::class, 'UpdateProductLable'])->middleware(['custom.auth','AdminApiLog']);
    Route::delete('deleteproductlable', [DashboardController::class, 'DeleteProductLable'])->middleware(['custom.auth','AdminApiLog']);
    Route::get('product-label-status', [DashboardController::class,'LableChangeStatus'])->middleware(['custom.auth','AdminApiLog']);
    
});

Route::post('{slug}/currency', [ApiController::class, 'currency'])->middleware(['custom.auth','APILog']);
