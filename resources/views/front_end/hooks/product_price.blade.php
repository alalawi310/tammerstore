@if (isset($product->is_sale_enable) && $product->is_sale_enable == true)
    <ins class="product_final_price"> {!! currency_format_with_sym(\App\Models\Product::ProductPrice($currentTheme, $slug, $product->id,$product->variant_id,($product->sale_price ?? $product->price)), $store->id, $currentTheme)  !!}</ins>
    @if (!empty($product->sale_price))
        <del class="product_orignal_price"> {!! currency_format_with_sym(\App\Models\Product::ProductPrice($currentTheme, $slug, $product->id,$product->variant_id,$product->price), $store->id, $currentTheme)  !!}</del>
    @endif
@else 
    @if (!empty($product->sale_price) && $product->sale_price < $product->price)
        <ins class="product_final_price"> {!! currency_format_with_sym(\App\Models\Product::ProductPrice($currentTheme, $slug, $product->id,$product->variant_id,$product->sale_price), $store->id, $currentTheme)  !!}</ins>
        <del class="product_orignal_price"> {!! currency_format_with_sym(\App\Models\Product::ProductPrice($currentTheme, $slug, $product->id,$product->variant_id,$product->price), $store->id, $currentTheme)  !!}</del>
    @else
        <ins class="product_final_price"> {!! currency_format_with_sym(\App\Models\Product::ProductPrice($currentTheme, $slug, $product->id,$product->variant_id,$product->price), $store->id, $currentTheme)  !!}</ins>
    @endif
@endif