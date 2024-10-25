<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        <a href="javascript:void(0)" class="btn btn-sm btn-primary"
            data-ajax-popup="true"
            data-size="xs" data-title="Create Support Tickets"
            data-url="{{ route('add.support.ticket',$slug) }}" data-toggle="tooltip"
            title="{{ __('Add Ticket') }}">
            <i class="ti ti-plus"></i>{{ __('Add Ticket') }}
        </a>
    </div>
</div>
<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col">{{ __('Title') }}</th>
            <th scope="col">{{ __('Ticket Id') }}</th>
            <th scope="col">{{ __('Order Id') }}</th>
            <th scope="col">{{ __('Customer') }}</th>
            <th scope="col">{{ __('Status') }}</th>
            <th scope="col"> {{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($tickets))
            @foreach ($tickets as $ticket)
                @php $order = \App\Models\Order::find($ticket->order_id);
                    $order_data = $order->order_detail($order->id);
                @endphp
                <tr>
                    <td> {{ $ticket->title }} </td>
                    <td>  #{{$ticket->ticket_id}}</td>
                    <td> {{ !empty($order_data['order_id']) ? $order_data['order_id'] : '-' }}</td>
                    <td> {{ $ticket->UserData->name ?? '-' }} </td>
                    <td> {{ $ticket->status }} </td>

                    <td class="text-end">
                        <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-info"
                            data-url="{{ route('get.support.ticket',[$slug,$ticket->id]) }}" data-size="lg"
                            data-ajax-popup="true" data-title="{{ __('Edit Ticket') }}" data-bs-toggle="tooltip"
                            title="{{__('Edit') }}">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button class="btn btn-sm  btn-primary"
                            data-url="{{ route('reply.support.ticket',[$slug,$ticket->id]) }}" data-size="lg"
                            data-ajax-popup="true" data-title="{{ __('Reply Ticket') }}" data-bs-toggle="tooltip"
                            title="{{__('Reply') }}">
                            <i class="fas fa-share"></i>
                        </button>
                        {!! Form::open(['method' => 'GET', 'route' => ['destroy.ticket', $slug, $ticket->id], 'class' => 'd-inline']) !!}
                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                            title="{{__('Delete') }}">
                                <i class="ti ti-trash" data-id="{{ $ticket->id }}"></i>
                            </button>
                        {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td><h2 class="">{{ __('No records found') }}</h2></td>
            </tr>
        @endif
    </tbody>
</table>
@if($tickets->lastItem() >= 10)
<div class="right-result-tbl text-right">
    @if (isset($tickets))
    <b>Showing {{ $tickets->firstItem() }}</b> to {{ $tickets->lastItem() }} of {{ $tickets->currentPage() }} ({{ $tickets->lastPage() }} Pages)
    @endif
</div>
<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if (isset($tickets) && $tickets->currentPage() < 1) {
                $previousPageUrl = $tickets->previousPageUrl();
            }
            if (isset($tickets) && $tickets->lastPage() > 1 && $tickets->currentPage() != $tickets->lastPage()) {
                $nextPageUrl = $tickets->nextPageUrl();
            }
        @endphp
        <button class="btn-secondary back-btn-acc" onclick="get_address('{{ $previousPageUrl }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
            {{ __('Back') }}
        </button>
        <button class="btn continue-btn" onclick="get_address('{{ $nextPageUrl }}')">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
        </button>
    </div>
</div>
@endif
