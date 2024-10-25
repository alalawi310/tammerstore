<div class="tab-pane fade show active" id="{{ $tab_name }}-home" role="tabpanel" aria-labelledby="{{ $tab_name }}-tab">
@if (isset($top_sales) && count($top_sales) > 0)    
    @foreach ($top_sales as $sale)
    <div class="cat-item d-flex flex-wrap align-items-center gap-3 justify-contant-between">
        <div class="cat-img rounded-1 border overflow-hidden">
            <img src="{{ asset($sale->sale_image_path ?? '#') }}"
                alt="product-img">
        </div>    
        <div class="cat-tab-text d-flex align-items-start gap-2">
            <p class="m-0 text-muted">{{ $sale->sale_name }}</p>
            <span class="cat-price"><b>{{ currency_format_with_sym(($sale->total_sale/100), auth()->user()->current_store, APP_THEME()) }}</b></span>
        </div>
    </div>
    @endforeach
@else
    <div class="d-flex text-center align-items-center h-100 w-100 justify-content-center">
        <span>{{ __('No Data Found') }}</span>
    </div>
@endif
</div>