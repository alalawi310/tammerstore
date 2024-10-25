@php
    $module = Nwidart\Modules\Facades\Module::find('QuickCheckout');
    $SkipCartmodule = Nwidart\Modules\Facades\Module::find('SkipCart');
    $store = \App\Models\Store::where('slug',$slug)->first();
    $user = \App\Models\User::find($store->created_by);
    $enable_quick_checkout =  \App\Models\Utility::GetValueByName('enable_quick_checkout',$currentTheme, $store->id);
    $enable_skip_cart =  \App\Models\Utility::GetValueByName('enable_skip_cart',$currentTheme, $store->id);
    $plan = \App\Models\Plan::find($user->plan_id);
@endphp
@if(isset($module) && $module->isEnabled())
    @if (isset($plan))
        @if (strpos($plan->modules, 'QuickCheckout') !== false)
            @if($enable_quick_checkout == 'on')
                @include('quickcheckout::theme.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
            @endif
        @endif
    @endif
@endif
@if(isset($SkipCartmodule) && $SkipCartmodule->isEnabled())
    @if (isset($plan))
        @if (strpos($plan->modules, 'SkipCart') !== false)
            @if($enable_skip_cart == 'on')
                @include('skipcart::theme.skip_cart_button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
            @endif
        @endif
    @endif
@endif
