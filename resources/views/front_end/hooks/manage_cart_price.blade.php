@if (module_is_active('ProductPricing') && isset($item->sale_price))
    <ins>{{ currency_format_with_sym($item->sale_price, $store->id, $currentTheme) }}
    </ins><del>{{ currency_format_with_sym($item->total_orignal_price, $store->id, $currentTheme) ?? SetNumberFormat($item->total_orignal_price) }}</del>
@else
    <ins>{{ currency_format_with_sym($item->final_price, $store->id, $currentTheme) }}
    </ins><del>{{ currency_format_with_sym($item->total_orignal_price, $store->id, $currentTheme) ?? SetNumberFormat($item->total_orignal_price) }}</del>
@endif
