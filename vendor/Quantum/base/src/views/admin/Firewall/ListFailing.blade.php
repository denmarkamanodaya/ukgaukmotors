@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Settings' => 'is_current', 'Firewall' => 'is_current', 'Failing IP\'s' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Firewall</span> - Manage Failing IP's
@stop


@section('content')    <div class="panel panel-default">
                <div class="panel-body">
                    IP's get listed here if they fail certain actions. Ie : Trying to log in using an invalid email/password.
                    Once an ip gets listed {{Config::get('firewall.failures_limit')}} times then they are added to the blocked IP list and removed from this section.
                    <br><br><b>Note:</b> Ips are held here for {{Config::get('firewall.failures_time')}} minutes per incident. They are then automatically cleared upon the next garbage collection.
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Ip Address</th>
                        <th>Block Reason</th>
                        <th>Blocked On</th>
                        <th>Releases On</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($failures as $failure)

                        <tr>
                            <td>{{ $failure->ip_address }}</td>
                            <td>{{ $failure->info }}</td>
                            <td>{{ $failure->created_at->format('dS M Y H:i:s') }}</td>
                            <td>{{ $failure->released->diffForHumans() }}</td>
                            <td>
                                {!! Form::open(['url' => 'admin/firewall/failure/remove']) !!}
                                {!! Form::hidden('id', $failure->id) !!}
                                {!! Form::button('<i class="far fa-times"></i> Remove', array('type' => 'submit', 'class' => 'btn btn-danger openbutton')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                {!! $failures->render() !!}
                    <div id="pagination-wrap">
                        <div class="pagination-info">Showing {!! $failures->count() !!} out of {!! $failures->total() !!} entries</div>
                        <div class="pagination-links">{!! $failures->render() !!}</div>
                    </div>


                </div>
            </div>
@stop