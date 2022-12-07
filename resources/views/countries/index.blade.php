@extends('layouts.admin')
@section('content')
@can('create-country')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.countries.create') }}">
                {{ trans('global.add') }} {{ trans('crud.countries.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('crud.countries.title') }} {{ trans('global.list') }}

        <input type="text" class="form-control form-control-sm" style="float: right; width: 300px;" name="dataTableSearch" id="dataTableSearch" value="{{ request()->input('s') }}" placeholder="Type and search in table" />
        <a href="javascript:;" id="dtToggleActionBtns" style="float: right; margin-right: 20px;" onclick="toggleGridOptions()"><i class="fa-fw nav-icon fas fa-cogs"></i></a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable datatable-Cities">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('crud.countries.fields.country_name') }}
                        </th>
                        <th>
                            {{ trans('crud.countries.fields.country_code') }}
                        </th>
                        <th>
                            {{ trans('crud.countries.fields.default_language') }}
                        </th>
                        <th>
                            {{ trans('crud.countries.fields.default_language_short_code') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $country)
                        <tr data-entry-id="{{ $country->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $country->country_name ?? '' }}
                            </td>
                            <td>
                                {{ $country->country_code ?? '' }}
                            </td>
                            <td>
                                {{ $country->defaultLanguage->language_name ?? '' }}
                            </td>
                            <td>
                                {{ $country->defaultLanguage->language_short_code ?? '' }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ trans('global.grid.actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @can('show-country')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.countries.show', $country->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('update-country')
                                            <li>
                                                <a class="dropdown-item dropdown-item-font" href="{{ route('admin.countries.edit', $country->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            </li>
                                        @endcan
                                        <li><hr class="dropdown-divider"></li>
                                        @can('delete-country')
                                            <li>
                                                <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST" id="frmDeleteCity-{{ $country->id }}" style="display: inline-block; width: 100%;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <a class="dropdown-item dropdown-item-font text-danger" href="javascript:;" onclick="deleteGridRecord('frmDeleteCity-{{ $country->id }}')">
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
            @if($countries->count())
                {{ $countries->links() }}
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

            @can('delete-country')
                let deleteButtonTrans = '{{ trans('global.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.countries.massDestroy') }}",
                    className: 'btn btn-sm btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            Swal.fire(
                                '{{ trans('global.message') }}!',
                                '{{ trans('global.grid.no_item_selected') }}',
                            )
                            return
                        }

                        Swal.fire({
                            title: '{{ trans('global.are_you_sure') }}',
                            text: '{{ trans('global.are_you_sure_delete_msg') }}',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    headers: {'x-csrf-token': _token},
                                    method: 'POST',
                                    url: config.url,
                                    data: { ids: ids, _method: 'DELETE' }
                                })
                                .done(function () {
                                    Swal.fire(
                                        '{{ trans('global.delete') }}!',
                                        '{{ trans('global.success') }}'
                                    );

                                    location.reload()
                                })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                paging: false,
                language: {
                    infoEmpty: "{{ trans('global.grid_no_data') }}",
                    @if ($countries->count())
                        info: '{{ trans('global.grid_pagination_count_status', [
                            'firstItem' => $countries->firstItem(),
                            'lastItem' => $countries->lastItem(),
                            'total' => $countries->total()
                        ]) }}',
                    @endif
                },
            });

            table = $('.datatable-Cities:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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

                    window.location.href = '{{ route("admin.countries.index") }}'+generateQueryString(requestParameters);
                } else {
                    table.search(this.value).draw();
                }
            });
        })
</script>
@endsection
