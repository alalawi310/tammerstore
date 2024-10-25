@php
    $module = Nwidart\Modules\Facades\Module::find('ProductBarCode');
@endphp
@if(isset($module) && $module->isEnabled())
@php
    $store = \App\Models\Store::where('slug',$slug)->first();
    $user  = \App\Models\User::find($store->created_by);
    $plan = \App\Models\Plan::find($user->plan_id);
@endphp
    @if (isset($plan))
        @if (strpos($plan->modules, 'ProductBarCode') !== false) 
            @include('productbarcode::pages.qrcode', ['product_id' => $item->product_id ?? null ,'slug' => $slug ?? null])
        @endif
    @endif
@endif
