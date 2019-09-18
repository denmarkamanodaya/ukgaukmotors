@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/plugins/datatables/datatables.min.js')}}"></script>
    <script>

           var eventstable = $('#events-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! url('admin/calendar/eventsData') !!}',
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'start_day',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'end_time', name: 'end_time'},
                    {data: 'repeat_year', name: 'repeat_year'},
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
                    { width: "10%"},
                    { width: "5%"},
                    { width: "5%"}
                ],
                language: {
                    "emptyTable":     "No Events Found"
                },
                order: [[ 0, 'asc' ]]
            });

    </script>
@stop

@section('page_css')

@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Events' => 'is_current')) !!}

@stop

@section('page-header')
    <span class="text-semibold">Support</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Events</h6>
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">

                    <div class="mb-20">
                        <a href="{{url('/admin/calendar/event/create')}}">
                            <button class="btn bg-teal-400 btn-labeled" type="button">
                                <b>
                                    <i class="far fa-list-alt"></i>
                                </b>
                                Create New Site Event
                            </button>
                        </a></div>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="events-table">
                        <thead>
                        <tr>
                            <th class="">Event</th>
                            <th class="">Start Day</th>
                            <th class="">Start Time</th>
                            <th class="">End Time</th>
                            <th class="">Repeats</th>
                            <th class="">Updated</th>
                        </tr>
                        </thead>

                    </table>


                </div>
            </div>

        </div>

    </div>



@stop


