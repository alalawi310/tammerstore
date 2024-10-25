@extends('layouts.app')

@section('page-title', __('Roles'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Roles') }}</li>
@endsection

@section('action-button')
    {{-- @permission('Create Role') --}}
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="xl" data-title="{{__('Create Role')}}"
                data-url="{{ route('roles.create') }}" data-toggle="tooltip" title="{{ __('Create Role') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    {{-- @endpermission --}}
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Permissions') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr class="border-0">
                                    <td class="border-0 capitalize">{{ $role->name }}</td>
                                    <td class="border-0">
                                        @foreach ($role->permissions()->pluck('name') as $permission)
                                            <span class="d-inline-block rounded-1 py-2 px-3 m-1 bg-dark ">
                                                <a href="#" class="text-dark f-12">{{ $permission }}</a>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex gap-1 justify-content-end">
                                        @permission('Edit Role')
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('roles.edit', $role->id) }}" data-size="xl"
                                                data-ajax-popup="true" data-title="{{ __('Edit Roles') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                        @endpermission
                                        {{-- @permission('Delete Role') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    function Checkall(module = null) {
        var ischecked = $("#checkall-" + module).prop('checked');
        if (ischecked == true) {
            $('.checkbox-' + module).prop('checked', true);
        } else {
            $('.checkbox-' + module).prop('checked', false);
        }

       // Get all checkboxes with IDs that start with 'module_checkbox_' and include the module
        var checkboxes = document.querySelectorAll('input[id^="module_checkbox_"]');

        // Check or uncheck all checkboxes based on the 'checkall' checkbox state
        checkboxes.forEach(function(checkbox) {
            var check = $("#checkall-" + module).prop('checked');
            if (checkbox.id.includes(module)) {
                checkbox.checked = check
            }
        });

        // Call CheckModule to update the module checkbox state
        CheckModule('module_checkbox_' + module);
    }

    function CheckModule(cl = null) {
        var ischecked = $("#" + cl).prop('checked');
        if (ischecked == true) {
            $('.' + cl).find("input[type=checkbox]").prop('checked', true);
        } else {
            $('.' + cl).find("input[type=checkbox]").prop('checked', false);
        }
    }

    function CheckPermission(cl = null, module = null) {
        var ischecked = $("#" + cl).prop('checked');
        var allChecked = true;

        // Check if all permissions for the given module are checked
        $('.' + module).find("input[type=checkbox]").each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false; // Exit the loop
            }
        });

        // Update the module checkbox based on the state of permissions
        if (allChecked) {
            $('#' + module).prop('checked', true);
        } else {
            $('#' + module).prop('checked', false);
        }
    }

    $(document).ready(function() {
        // Attach the CheckPermission function to all permission checkboxes
        $(document).on('change', 'input[type=checkbox]', function() {
            var id = $(this).attr('id');
            var module = $(this).data('module');
            CheckPermission(id, module);
        });
    });
</script>

@endpush
