<table class="table dataTable">
    <thead>
        <tr>
            <th>{{ __('Store Name') }}</th>
            <th>{{ __('Active/Deactive') }}</th>
            <th class="text-end">{{ __('Store Links') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stores as $store)
            <tr>
                <td>{{ $store->name }}</td>
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" data-id="{{$store->id}}"  class="form-check-input active-store-index" name="is_popular"
                        id="active_store_{{$store->id}}" value="{{$store->is_active}}" {{ $store->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="active_store_{{$store->id}}"></label>
                    </div>
                </td>
                <td class="text-end">
                    <input type="text" value="{{ route('landing_page', $store->slug) }}" id="myInput_{{ $store->slug }}"
                        class="form-control d-inline-block theme-link" aria-label="Recipient's username"
                        aria-describedby="button-addon2" readonly>
                    <button class="btn btn-outline-primary" type="button"
                        onclick="myFunction('myInput_{{ $store->slug }}')" id="button-addon2"><i
                            class="far fa-copy"></i>
                        {{ __('Store Link') }}</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script type="text/javascript">
    function myFunction(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        show_toastr('Success', "{{ __('Link copied') }}", 'success');
    }

    $(document).on('change', '.active-store-index',function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('store.active.status') }}",
            data: {'status': status, 'id': id},
            success: function(data){
                $('#loader').fadeOut();
                if (data.status != 'success') {
                    show_toastr('Error', data.message, 'error');
                } else {
                    show_toastr('Success', data.message, 'success');
                }
            }
        });
    });
</script>
