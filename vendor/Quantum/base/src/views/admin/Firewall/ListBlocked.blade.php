@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Settings' => 'is_current', 'Firewall' => 'is_current', 'Blocked IP\'s' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Firewall</span> - Manage Blocked IP's
@stop


@section('content')
            <div class="panel panel-default">
                <div class="panel-body">
                    IP's get listed here only after they have been on the Failing IP list for {{Config::get('firewall.failures_limit')}} times within {{Config::get('firewall.failures_time')}} minutes. All listed Ips are blocked from any actions within the site
                    for {{Config::get('firewall.ban_time')}} minutes and will be automatically released once the ban time has expired.
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
                        @foreach($lockouts as $failure)

                            <tr>
                                <td>{{ $failure->ip_address }}</td>
                                <td>{{ $failure->info }}</td>
                                <td>{{ $failure->created_at->format('dS M Y H:i:s') }}</td>
                                <td>{{ $failure->released->diffForHumans() }}</td>
                                <td>
                                    {!! Form::open(['url' => 'admin/firewall/blocked/remove']) !!}
                                    {!! Form::hidden('id', $failure->id) !!}
                                    {!! Form::button('<i class="far fa-times"></i> Remove', array('type' => 'submit', 'class' => 'btn btn-danger openbutton')) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div id="pagination-wrap">
                        <div class="pagination-info">Showing {!! $lockouts->count() !!} out of {!! $lockouts->total() !!} entries</div>
                        <div class="pagination-links">{!! $lockouts->render() !!}</div>
                    </div>

                </div>
            </div>

@stop