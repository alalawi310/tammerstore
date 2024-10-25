@php
    $module = Nwidart\Modules\Facades\Module::find('PartialPayments');
    $store = \App\Models\Store::where('slug', $slug)->first();
    if ($store) {
        $user = \App\Models\User::find($store->created_by);
        $plan = \App\Models\Plan::find($user->plan_id);
    }
    $order_data = \App\Models\Order::find($order->id);
    $order_status = \App\Models\Utility::GetValueByName('set_order_status',$store->theme_id, $store->id);
    @endphp
@if(isset($module) && $module->isEnabled())
@if ($plan && in_array('PartialPayments', explode(',', $plan->modules)) && isset($pending_order) && $pending_order != null && $order_data['delivered_status'] == 7 && $order_status != 'null')
@php
    $pending_order = \Modules\PartialPayments\app\Models\OrderPartialPayments::where('order_id',$order_data->product_order_id)->where('payment_status','Pending payment')->whereNot('payment_amount',0)->first();
@endphp
    @include('partialpayments::theme.payment-button', ['order' => $order ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
@endif
@endif
