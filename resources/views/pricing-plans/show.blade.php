@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('crud.pricing_plans.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-sm btn-primary" href="{{ route('admin.pricing-plans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_name') }}
                        </th>
                        <td>
                            {{ $pricingPlan->pricing_plan_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_type') }}
                        </th>
                        <td>
                            {{ \App\Models\PricingPlan::PLAN_TYPE[$pricingPlan->pricing_plan_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_payment_frequency') }}
                        </th>
                        <td>
                            {{ \App\Models\PricingPlan::PLAN_PAYMENT_FREQUENCY[$pricingPlan->pricing_plan_payment_frequency] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_duration') }}
                        </th>
                        <td>
                            {{ $pricingPlan->pricing_plan_duration ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.amount') }}
                        </th>
                        <td>
                            {{ $pricingPlan->planLatestPrice->amount ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.tax') }}
                        </th>
                        <td>
                            {{ $pricingPlan->planLatestPrice->tax ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.total_amount') }}
                        </th>
                        <td>
                            {{ $pricingPlan->planLatestPrice->total_amount ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('crud.pricing_plans.title_singular') }} History

        <input type="text" class="form-control form-control-sm" style="float: right; width: 300px;" name="dataTableSearch" id="dataTableSearch" value="{{ request()->input('s') }}" placeholder="Type and search in table" />
        <a href="javascript:;" id="dtToggleActionBtns" style="float: right; margin-right: 20px;" onclick="toggleGridOptions()"><i class="fa-fw nav-icon fas fa-cogs"></i></a>
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table datatable datatable-PricingPlanHistories">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('crud.pricing_plan_histories.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plan_histories.fields.tax') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plan_histories.fields.total_amount') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plan_histories.fields.valid_from') }}
                        </th>
                        <th>
                            {{ trans('crud.pricing_plan_histories.fields.valid_to') }}
                        </th>
                        {{-- <th>
                            &nbsp;
                        </th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricingPlan->pricingPlanHistories as $pricingPlanHistory)
                        <tr data-entry-id="{{ $pricingPlanHistory->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $pricingPlanHistory->amount }}
                            </td>
                            <td>
                                {{ $pricingPlanHistory->tax ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlanHistory->total_amount ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlanHistory->valid_from ?? '' }}
                            </td>
                            <td>
                                {{ $pricingPlanHistory->valid_to ?? '' }}
                            </td>
                            {{-- <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ trans('global.grid.actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @can('delete-pricing-plan')
                                        <li>
                                            <form action="{{ route('admin.pricing-plan-histories.destroy', ['pricing_plan_history' => $pricingPlanHistory->id]) }}" method="POST" id="frmDeletePricingPlanHistory-{{ $pricingPlanHistory->id }}" style="display: inline-block; width: 100%;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a class="dropdown-item dropdown-item-font text-danger" href="javascript:;" onclick="deleteGridRecord('frmDeletePricingPlanHistory-{{ $pricingPlanHistory->id }}')">
                                                    {{ trans('global.delete') }}
                                                </a>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                /* let deleteButtonTrans = '{{ trans('global.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.pricing-plan-histories.massDestroy') }}",
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
                dtButtons.push(deleteButton) */
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                paging: false,
                language: {
                    infoEmpty: "{{ trans('global.grid_no_data') }}",
                },
            });

            table = $('.datatable-PricingPlanHistories:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            // datatable search functionality
            $('#dtToggleActionBtns').tooltip({'trigger':'hover', 'title': '{{ trans('global.grid.toggle_action_buttons_tooltip') }}'});
        })
</script>
@endsection
