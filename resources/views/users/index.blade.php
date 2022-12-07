@extends('layouts.admin')
@section('content')
{{-- @can('create-user')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{  route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('crud.users.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}

<div class="card">
    <div class="card-header">
        {{ trans('crud.users.title') }} {{ trans('global.list') }}

        <input type="text" class="form-control form-control-sm" style="float: right; width: 300px;" name="dataTableSearch" id="dataTableSearch" value="{{ request()->input('s') }}" placeholder="Type and search in table" />
        <a href="javascript:;" id="dtToggleActionBtns" style="float: right; margin-right: 20px;" onclick="toggleGridOptions()"><i class="fa-fw nav-icon fas fa-cogs"></i></a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable datatable-Users">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('crud.users.fields.name') }}
                        </th>
                        {{-- <th>
                            {{ trans('crud.users.fields.email') }}
                        </th> --}}
                        <th>
                            {{ trans('crud.users.fields.user_phone') }}
                        </th>
                        <th>
                            {{ trans('crud.users.fields.signup_date') }}
                        </th>
                        <th>
                            {{ trans('crud.users.fields.plan_type') }}
                        </th>
                        <th>
                            {{ trans('crud.users.fields.user_type') }}
                        </th>
                        {{-- <th>
                            {{ trans('crud.users.fields.user_name') }}
                        </th> --}}
                        <th>
                            {{ trans('crud.users.fields.birthday') }}
                        </th>
                        <th>
                            {{ trans('crud.users.fields.gender') }}
                        </th>
                        <th>
                            {{ trans('crud.users.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>
                                <span style="display: none;">{{ $user->id }}</span>
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $user->email ?? '' }}
                            </td> --}}
                            <td>
                                {{ $user->user_phone ?? '' }}
                            </td>

                            <td>
                                {{ $user->created_at ?? '' }}
                            </td>
                            <td>
                                n/a
                            </td>

                            <td>
                                {{ Str::headline($user->user_type) ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $user->user_name ?? '' }}
                            </td> --}}
                            <td>
                                {{ $user->birthday ?? 'n/a' }}
                            </td>
                            <td>
                                {{ $user->gender ?? 'n/a' }}
                            </td>
                            <td>
                                @if ($user->is_active == 1)
                                <span class="badge text-bg-success">{{ trans('global.activated') }}</span>
                                @elseif ($user->is_active == 2)
                                <span class="badge text-bg-warning">{{ trans('global.deactivated') }}</span>
                                @elseif ($user->is_active == 3)
                                    <span class="badge text-bg-danger">{{ trans('global.deleted') }}</span>
                                @else
                                    'n/a'
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ trans('global.grid.actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        {{-- @can('show-user')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.users.show', $user->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('update-user')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.users.edit', $user->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        </li>
                                        @endcan --}}
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="javascript:;">
                                                Personal details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="javascript:;">
                                                Address details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="javascript:;">
                                                Interests
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="javascript:;">
                                                Transaction details
                                            </a>
                                        </li>
                                        @can('delete-user')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="POST" id="frmDeleteUser-{{ $user->id }}" style="display: inline-block; width: 100%;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a class="dropdown-item dropdown-item-font text-danger" href="javascript:;" onclick="deleteGridRecord('frmDeleteUser-{{ $user->id }}')">
                                                    {{ trans('global.delete') }}
                                                </a>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($users->count())
                {{ $users->links() }}
            @endif
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
    <script>
        var table;
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                paging: false,
                language: {
                    infoEmpty: "{{ trans('global.grid_no_data') }}",
                    @if ($users->count())
                        info: '{{ trans('global.grid_pagination_count_status', [
                            'firstItem' => $users->firstItem(),
                            'lastItem' => $users->lastItem(),
                            'total' => $users->total()
                        ]) }}',
                    @endif
                },
            });

            table = $('.datatable-Users:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            // datatable search functionality
            $('#dtToggleActionBtns').tooltip({'trigger':'hover', 'title': '{{ trans('global.grid.toggle_action_buttons_tooltip') }}'});
            $('#dataTableSearch').tooltip({'trigger':'focus', 'title': '{{ trans('global.grid.search_tooltip') }}'});

            $('#dataTableSearch').on( 'keyup', function (e) {
                if(e.which == 13) { // if user press enter in search text input
                    let requestParameters = [];

                    let searchText = $('#dataTableSearch').val();
                    if($.trim(searchText) != '') {
                        requestParameters.push('s='+$.trim(searchText));
                    }

                    window.location.href = '{{ route("admin.users.index") }}'+generateQueryString(requestParameters);
                } else {
                    table.search(this.value).draw();
                }
            });
        })
</script>
@endsection
