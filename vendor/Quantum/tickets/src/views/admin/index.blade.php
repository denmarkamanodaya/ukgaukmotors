@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/plugins/datatables/datatables.min.js')}}"></script>
    <script>

            var opentable = $('#opentickets-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('admin/tickets/openTickets') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'status.name', name: 'status.name'},
                    {data: 'department.name', name: 'department.name'},
                    {data: 'updated_at', name: 'updated_at'}
                ],
                columnDefs: [
                    { width: "5%", 'targets': 0, 'searchable':false, 'orderable':false},
                    { width: "60%"},
                    { width: "10%"},
                    { width: "10%"},
                    { width: "15%"}
                ],
                language: {
                    "emptyTable":     "No New Tickets Found"
                },
                order: [[ 4, 'desc' ]]
            });

           var repliedtable = $('#repliedtickets-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('admin/tickets/repliedTickets') !!}',
                columns: [
                    {data: 'id', name: 'id'},
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
                    { width: "5%", 'targets': 0, 'searchable':false, 'orderable':false},
                    { width: "60%"},
                    { width: "10%"},
                    { width: "10%"},
                    { width: "15%"}
                ],
                language: {
                    "emptyTable":     "No Replied Tickets Found"
                },
                order: [[ 4, 'desc' ]]
            });

            var closedtable = $('#closedtickets-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('admin/tickets/closedTickets') !!}',
                columns: [
                    {data: 'id', name: 'id'},
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
                    { width: "5%", 'targets': 0, 'searchable':false, 'orderable':false},
                    { width: "60%"},
                    { width: "10%"},
                    { width: "10%"},
                    { width: "15%"}
                ],
                language: {
                    "emptyTable":     "No Closed Tickets Found"
                },
                order: [[ 4, 'desc' ]]
            });

        $('#repliedcheckall').on('click', function(){
            // Check/uncheck all checkboxes in the table
            var rows = repliedtable.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#opencheckall').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = opentable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

        $('#closedcheckall').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = closedtable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });
    </script>
@stop

@section('page_css')

@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Tickets' => 'is_current')) !!}

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
                <li class="active"><a data-toggle="tab" href="#open" aria-expanded="false"><i class="far fa-ticket-alt position-left"></i> Open Tickets {!! ticketCountIcon('Open', $ticketCount) !!}</a></li>
                <li class=""><a data-toggle="tab" href="#replied" aria-expanded="true"><i class="far fa-comments position-left"></i> Replied Tickets {!! ticketCountIcon('Replied', $ticketCount) !!}</a></li>
                <li class=""><a data-toggle="tab" href="#closed" aria-expanded="true"><i class="far fa-times position-left"></i> Closed Tickets {!! ticketCountIcon('Closed', $ticketCount) !!}</a></li>
            </ul>

            <div class="navbar-right">

            </div>
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
                                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/deleteTickets')) !!}
                                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="opentickets-table">
                                        <thead>
                                        <tr>
                                            <th class=""><input type='checkbox' id='opencheckall' value=""> All</th>
                                            <th class="">Subject</th>
                                            <th class="">Status</th>
                                            <th class="">Department</th>
                                            <th class="">Updated</th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <div class="text-left">
                                        <button type="submit" class="btn btn-warning">Delete Selected Tickets<i class="far fa-times position-right"></i></button>
                                    </div>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                            <!-- /open tickets -->

                        </div>
                    </div>
                    <div id="replied" class="tab-pane fade">
                        <div class="" id="settings">

                            <!-- open tickets -->
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Replied Tickets</h6>
                                    <div class="heading-elements">

                                    </div>
                                </div>

                                <div class="panel-body">
                                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/deleteTickets')) !!}
                                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="repliedtickets-table">
                                        <thead>
                                        <tr>
                                            <th class=""><input type='checkbox' id='repliedcheckall' value=""> All</th>
                                            <th class="">Subject</th>
                                            <th class="">Status</th>
                                            <th class="">Department</th>
                                            <th class="">Updated</th>
                                        </tr>
                                        </thead>

                                    </table>
                                    <div class="text-left">
                                        <button type="submit" class="btn btn-warning">Delete Selected Tickets<i class="far fa-times position-right"></i></button>
                                    </div>
                                    {!! Form::close() !!}

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
                                {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/deleteTickets')) !!}
                                <table class="table datatable-ajax table-bordered table-striped table-hover" id="closedtickets-table">
                                    <thead>
                                    <tr>
                                        <th class=""><input type='checkbox' id='closedcheckall' value=""> All</th>
                                        <th class="">Subject</th>
                                        <th class="">Status</th>
                                        <th class="">Department</th>
                                        <th class="">Updated</th>
                                    </tr>
                                    </thead>

                                </table>
                                <div class="text-left">
                                    <button type="submit" class="btn btn-warning">Delete Selected Tickets<i class="far fa-times position-right"></i></button>
                                </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>



@stop


