@if (module_is_active('ProductPricing') && isset($item['sale_price']))
{{ currency_format_with_sym($item['sale_price'] ?? 0, $store->id, $store->theme_id) ?? SetNumberFormat($item['sale_price']) }}
@else
{{ currency_format_with_sym($item['final_price'] ?? 0, $store->id, $store->theme_id) ?? SetNumberFormat($item['final_price']) }}
@endif

