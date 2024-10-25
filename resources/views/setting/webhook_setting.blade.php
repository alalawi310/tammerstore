<div class="card" id="Webhook_Setting">
    <div class="card-header">
        <div class="row g-0">
            <div class="col-6">
                <h5> {{ __('Webhook Settings') }} </h5>
                <small>{{ __('Edit your Webhook Settings') }}</small>
            </div>
            <div class="col-6 text-end">
                <a href="javascript:;" class="btn btn-sm btn-icon btn-primary me-2" data-ajax-popup="true"
                    data-url="{{ route('webhook.create') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create') }}" data-title="{{ __('Create New Webhook') }}">
                    <i data-feather="plus"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="">
        <div class="row g-0">
            <div class="card-body table-border-style">
                <div class="datatable-container">
                    <div class="table-responsive custom-field-table">
                        <table class="table dataTable-table" id="pc-dt-simple" data-repeater-list="fields">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('module') }}</th>

                                    <th>{{ __('url') }}</th>
                                    <th>{{ __('method') }}</th>
                                    <th style="width: 200px;">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($webhooks as $webhook)
                            <tbody>
                                <td>{{ $webhook->module }}</td>
                                <td>{{ $webhook->url }}</td>
                                <td>{{ $webhook->method }}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-primary me-2"
                                            data-url="{{ route('webhook.edit', $webhook->id) }}" data-size="md"
                                            data-ajax-popup="true" data-title="{{ __('Edit webhook') }}">
                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                        </button>

                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['webhook.destroy', $webhook->id],
                                        'class' => 'd-inline',
                                        ]) !!}
                                        <button type="button" class="btn btn-sm btn-danger show_confirm">
                                            <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                title="Delete"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>