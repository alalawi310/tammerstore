<div class="order-confirm-details">
    <h5> {{ __('Product informations') }} :</h5>
    <ul>
        @if (!empty($response->data->cart_total_product))
            @foreach ($response->data->product_list as $item)
            {!! \App\Models\Product::ManageCheckoutProductPrice($item, $store, $store->theme_id) !!}
            @endforeach
        @endif
    </ul>
</div>
