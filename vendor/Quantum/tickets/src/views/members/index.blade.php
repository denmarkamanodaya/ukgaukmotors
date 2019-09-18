@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/plugins/datatables/datatables.min.js')}}"></script>
    <script>

        $(function() {
            $('#opentickets-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('members/support/openTickets') !!}',
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'status.name', name: 'status.name'},
                    {data: 'department.name', name: 'department.name'},
                    {data: 'updated_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                columnDefs: [
                    { width: "60%"},
                    { width: "10%"},
                    { width: "10%"},
                    { width: "20%"}
                ],
                language: {
                    "emptyTable":     "No Open Tickets Found"
                },
                order: [[ 3, 'desc' ]]
            });
        });

        $(function() {
            $('#closedtickets-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('members/support/closedTickets') !!}',
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'status.name', name: 'status.name'},
                    {data: 'department.name', name: 'department.name'},
                    {data: 'updated_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                columnDefs: [
                    { width: "60%"},
                    { width: "10%"},
                    { width: "10%"},
                    { width: "20%"}
                ],
                language: {
                    "emptyTable":     "No Closed Tickets Found"
                },
                order: [[ 3, 'desc' ]]
            });
        });

    </script>
@stop

@section('page_css')

@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Support' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Support</span>
@stop


@section('content')

    <div class="navbar navbar-default navbar-component navbar-xs">
        <ul class="nav navbar-nav visible-xs-block">
            <li class="full-width text-center"><a data-target="#navbar-filter" data-toggle="collapse"><i class="icon-menu7"></i></a></li>
        </ul>

        <div id="navbar-filter" class="navbar-collapse collapse">
            <ul class="nav navbar-nav element-active-slate-400">
                <li class="active"><a data-toggle="tab" href="#open" aria-expanded="false"><i class="far fa-ticket-alt position-left"></i> Open Tickets</a></li>
                <li class=""><a data-toggle="tab" href="#closed" aria-expanded="true"><i class="far fa-times position-left"></i> Closed Tickets</a></li>
            </ul>

            <div class="navbar-right">

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 text-center mb-20">
            <a href="{{url('/members/support/createTicket')}}">
                <button class="btn bg-teal-400 btn-labeled" type="button">
                    <b>
                        <i class="far fa-life-ring"></i>
                    </b>
                    Open a new support request
                </button>
            </a>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

            <div class="tabbable">
                <div class="tab-content">
                    <div id="open" class="tab-pane fade active in">
                        <div class="" id="settings">

                            <!-- open tickets -->
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Open Tickets</h6>
                                    <div class="heading-elements">

                                    </div>
                                </div>

                                <div class="panel-body">

                                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="opentickets-table">
                                        <thead>
                                        <tr>
                                            <th class="col-lg-7">Subject</th>
                                            <th class="col-lg-2">Status</th>
                                            <th class="col-lg-1">Department</th>
                                            <th class="col-lg-2">Updated</th>
                                        </tr>
                                        </thead>

                                    </table>

                                </div>
                            </div>
                            <!-- /open tickets -->

                        </div>
                    </div>
                    <div id="closed" class="tab-pane fade">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Closed Tickets</h6>
                                <div class="heading-elements">

                                </div>
                            </div>

                            <div class="panel-body">
                                <table class="table datatable-ajax table-bordered table-striped table-hover" id="closedtickets-table">
                                    <thead>
                                    <tr>
                                        <th class="col-lg-7">Subject</th>
                                        <th class="col-lg-2">Status</th>
                                        <th class="col-lg-1">Department</th>
                                        <th class="col-lg-2">Updated</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>



@stop


