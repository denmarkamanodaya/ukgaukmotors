@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_commerce_payments_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'payment_gateway.title', name: 'payment_gateway.title'},
                    {data: 'trx_id', name: 'trx_id'},
                    {data: 'type', name: 'type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'user.username', name: 'user.username'},
                    {data: 'created_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                order: [[ 0, 'desc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Commerce Logs' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Site Payments</span> - Past 90 days
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Transactions</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="payments-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-2">Payment Gateway</th>
                            <th class="col-lg-2">Trans Id</th>
                            <th class="col-lg-1">Type</th>
                            <th class="col-lg-1">Amount</th>
                            <th class="col-lg-1">User</th>
                            <th class="col-lg-2">When</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


