@if (module_is_active('ProductPricing') && isset($item->sale_price))
    <ins>{{ currency_format_with_sym($item->sale_price, $store->id, $currentTheme) ?? SetNumberFormat($item->sale_price) }}
    </ins>
@else
    <ins>{{ currency_format_with_sym($item->final_price, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }}
    </ins>
@endif
