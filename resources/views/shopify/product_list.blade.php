<tbody>
    @foreach ($products as $product_data)
        <tr>
            <td>
                <img src="{{ !empty($product_data['image']) ? get_file($product_data['image']['src'], APP_THEME()) : asset(Storage::url('uploads/woocommerce.png')) }}" alt="" width="100">
            </td>
            <td>{{ $product_data['title'] }}</td>
            <td>{{ $product_data['product_type'] }}</td>
            @if($product_data['variants'][0]['title'] == 'Default Title')
                <td>{{ __('no variant') }}</td>
            @else
                <td>{{ __('in variant') }}</td>
            @endif
            <td class="text-end">
                @if (in_array($product_data['id'], $upddata))
                    <a href="{{ route('shopify_product.edit', $product_data['id']) }}" class="btn btn-sm btn-info" data-title="{{ __('Sync Again') }}">
                        <i class="ti ti-refresh" data-bs-toggle="tooltip" title="Sync Again"></i>
                    </a>
                @else
                    <a href="{{ route('shopify_product.show', $product_data['id']) }}" class="btn btn-sm btn-primary" data-title="{{ __('Add Product') }}">
                        <i class="ti ti-plus" data-bs-toggle="tooltip" title="{{ __('Add Product') }}"></i>
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
