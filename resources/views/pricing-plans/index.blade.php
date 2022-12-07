@extends('layouts.admin')
@section('content')
@can('create-pricing-plan')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{  route('admin.pricing-plans.create') }}">
                {{ trans('global.add') }} {{ trans('crud.pricing_plans.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('crud.pricing_plans.title') }} {{ trans('global.list') }}

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
                            {{ trans('crud.pricing_plans.fields.pricing_plan_name') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_type') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_payment_frequency') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_duration') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.tax') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plans.fields.total_amount') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricingPlans as $pricingPlan)
                        <tr data-entry-id="{{ $pricingPlan->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $pricingPlan->pricing_plan_name ?? '' }}
                            </td>
                            <td>
                                {{ \App\Models\PricingPlan::PLAN_TYPE[$pricingPlan->pricing_plan_type] ?? '' }}
                            </td>
                            <td>
                                {{ \App\Models\PricingPlan::PLAN_PAYMENT_FREQUENCY[$pricingPlan->pricing_plan_payment_frequency] ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlan->pricing_plan_duration ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlan->planLatestPrice->amount ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlan->planLatestPrice->tax ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlan->planLatestPrice->total_amount ?? '' }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ trans('global.grid.actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @can('show-pricing-plan')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.pricing-plans.show', $pricingPlan->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('update-pricing-plan')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.pricing-plans.edit', $pricingPlan->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('read-pricing-plan')
                                        <li>
                                            <a class="dropdown-item dropdown-item-font" href="{{ route('admin.pricing-plans.pricing-plan-details.index', ['pricing_plan' => $pricingPlan->id]) }}">
                                                {{ trans('crud.pricing_plans.fields.details') }}
                                            </a>
                                        </li>
                                        @endcan
                                        @can('delete-pricing-plan')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.pricing-plans.destroy', ['pricing_plan' => $pricingPlan->id]) }}" method="POST" id="frmDeleteCity-{{ $pricingPlan->id }}" style="display: inline-block; width: 100%;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a class="dropdown-item dropdown-item-font text-danger" href="javascript:;" onclick="deleteGridRecord('frmDeleteCity-{{ $pricingPlan->id }}')">
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
            @if($pricingPlans->count())
                {{ $pricingPlans->links() }}
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

            @can('delete-pricing-plan')
                let deleteButtonTrans = '{{ trans('global.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.pricing-plans.massDestroy') }}",
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
                    @if ($pricingPlans->count())
                        info: '{{ trans('global.grid_pagination_count_status', [
                            'firstItem' => $pricingPlans->firstItem(),
                            'lastItem' => $pricingPlans->lastItem(),
                            'total' => $pricingPlans->total()
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

                    window.location.href = '{{ route("admin.pricing-plans.index") }}'+generateQueryString(requestParameters);
                } else {
                    table.search(this.value).draw();
                }
            });
        })
</script>
@endsection
