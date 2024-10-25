@if (module_is_active('ProductPricing') && isset($item->sale_price))
    <li>{{ $item->qty }} {{ $item->name }}
        ({{ currency_format_with_sym($item->sale_price, $store->id, $currentTheme) ?? SetNumberFormat($item->sale_price) }})
    </li>
@else
    <li>{{ $item->qty }} {{ $item->name }}
        ({{ currency_format_with_sym($item->final_price, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }})
    </li>
@endif
