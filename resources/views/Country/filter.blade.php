<table class="table mb-0  dataTable">
<thead>
    <tr>
        <th>{{ __('Name') }}</th>
        <th class="text-center">{{ __('country') }}</th>
        <th class="text-end">{{ __('Action') }}</th>
    </tr>
</thead>
<tbody class="font-style">
@if(count($filter_country) > 0)
    @foreach($filter_country as $state)
        <tr>
            <td>{{ $state->name }}</td>
            <td class="text-center">{{ $state->country->name ?? '-' }}</td>
            <td class="text-end">
                <div class="d-flex gap-1">
                <button class="btn btn-sm btn-info"
                    data-url="{{ route('state.edit', $state['id']) }}"
                    data-size="md" data-ajax-popup="true"
                    data-title="{{ __('Edit State') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                    <i class="ti ti-pencil" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="edit"></i>
                </button>

                {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['state.destroy', $state['id']],
                    'class' => 'd-inline',
                ]) !!}
                <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                    <i class="ti ti-trash"></i>
                </button>
                {!! Form::close() !!}
                </div>
            </td>
        </tr>
    @endforeach
@else
    <!-- <td class="dataTables-empty" colspan="2">No entries found</td> -->
@endif
</tbody>
</table>