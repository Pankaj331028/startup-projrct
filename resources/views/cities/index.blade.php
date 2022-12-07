@extends('layouts.admin')
@section('content')
@can('create-city')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{  route('admin.cities.create') }}">
                {{ trans('global.add') }} {{ trans('crud.cities.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route("admin.cities.index") }}">
            @csrf
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="required" for="country">Country</label>
                        <select class="form-control select2" name="country" id="country" multiple required>
                            @foreach($countries as $id => $value)
                                <option value="{{ $id }}" @if(in_array($id, $selectedCountries)) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <button id="btnSearch" type="button" class="btn btn-success" style="margin-top: 32px;">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('crud.cities.title') }} {{ trans('global.list') }}

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
                            {{ trans('crud.cities.fields.city_name') }}
                        </th>
                        <th>
                            {{ trans('crud.cities.fields.country') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $key => $city)
                        <tr data-entry-id="{{ $city->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $city->city_name ?? '' }}
                            </td>
                            <td>
                                {{ $city->country->country_name ?? '' }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ trans('global.grid.actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @can('show-city')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.cities.show', $city->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('update-city')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.cities.edit', $city->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('delete-city')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" id="frmDeleteCity-{{ $city->id }}" style="display: inline-block; width: 100%;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a class="dropdown-item dropdown-item-font text-danger" href="javascript:;" onclick="deleteGridRecord('frmDeleteCity-{{ $city->id }}')">
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
            @if($cities->count())
                {{ $cities->links() }}
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

            @can('delete-city')
                let deleteButtonTrans = '{{ trans('global.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.cities.massDestroy') }}",
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
                    @if ($cities->count())
                        info: '{{ trans('global.grid_pagination_count_status', [
                            'firstItem' => $cities->firstItem(),
                            'lastItem' => $cities->lastItem(),
                            'total' => $cities->total()
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

                    let country = $("#country").val();
                    if(country.length) {
                        requestParameters.push('country='+country.join(','));
                    } else {
                        requestParameters.push('country=all');
                    }

                    let searchText = $('#dataTableSearch').val();
                    if($.trim(searchText) != '') {
                        requestParameters.push('s='+$.trim(searchText));
                    }

                    window.location.href = '{{ route("admin.cities.index") }}'+generateQueryString(requestParameters);
                } else {
                    table.search(this.value).draw();
                }
            });
        })

        $("#btnSearch").click(function() {
            let country = $("#country").val();
            if(country.length) {
                window.location.href = '{{ route("admin.cities.index") }}?country='+country.join(",");
            } else {
                Swal.fire({
                    title: '{{ trans('global.warning') }}!',
                    text: '{{ trans('global.please_select', ['option' => 'Country']) }}',
                    icon: 'warning',
                    cancelButtonColor: '#3085d6',
                })
            }
        });
</script>
@endsection
