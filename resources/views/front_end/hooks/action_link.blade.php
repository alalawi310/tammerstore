<div class="product-btn-wrp">
    @php
        $module = Nwidart\Modules\Facades\Module::find('ProductQuickView');
        $compare_module = Nwidart\Modules\Facades\Module::find('ProductCompare');
        $productcsv_module = Nwidart\Modules\Facades\Module::find('ProductCSV');
        $products_pdf_module = Nwidart\Modules\Facades\Module::find('ProductsPDF');
        $store = \App\Models\Store::where('slug', $slug)->first();
        if ($store) {
            $user = \App\Models\User::find($store->created_by);
            $plan = \App\Models\Plan::find($user->plan_id);
        }
    @endphp
    @if(isset($module) && $module->isEnabled())
        {{-- Include the module blade button --}}
        @if ($plan && in_array('ProductQuickView', explode(',', $plan->modules)))
            @include('productquickview::pages.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
        @endif
    @endif
    @if(isset($compare_module) && $compare_module->isEnabled())
        {{-- Include the module blade button --}}
        @if ($plan && in_array('ProductCompare', explode(',', $plan->modules)))
            @include('productcompare::pages.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
        @endif
    @endif

    @if (isset($productcsv_module) && $productcsv_module->isEnabled())
        {{-- Include the module blade button --}}
        @if ($plan && in_array('ProductCSV', explode(',', $plan->modules)))
            @include('productcsv::pages.button', [
                'product_slug' => $product->slug ?? null,
                'slug' => $slug ?? null,
                'currentTheme' => $currentTheme,
            ])
        @endif
    @endif

    @if (isset($products_pdf_module) && $products_pdf_module->isEnabled())
        @if ($plan && in_array('ProductsPDF', explode(',', $plan->modules)))
            {{-- Include the module blade button --}}
            @include('productspdf::pages.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
        @endif
    @endif
</div>
