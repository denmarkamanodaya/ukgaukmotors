@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Settings' => 'is_current', 'Firewall' => 'is_current', 'Whitelisted IP\'s' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Firewall</span> - Manage Whitelist
@stop


@section('content')
            <div class="panel panel-default">
                <div class="panel-body">
                    IP's listed here are ignored from blockage if certain undesirable actions are taken, IE: constant incorrect login attempts.
                </div>
            </div>

            <div class="row>">

            <div class="addWhitelist">
                {!! Form::open(['url' => 'admin/firewall/whitelist/add', 'class' => 'form-inline']) !!}


                <!-- ip_address input -->
                <div class="form-group">
                    {!! Form::label('ip_address', 'Whitelist an IP Address:', ['class' => 'control-label']) !!}
                    {!! Form::text('ip_address', null,['class' => 'form-control', 'placeholder' => 'IP Address', 'id' => 'ip_address', 'required']) !!}
                    @if ($errors->has('ip_address'))
                        <script>formErrors.push("ip_address");</script>
                        <span class="help-block validation-error-label" for="ip_address">{!! $errors->first('ip_address') !!}</span>
                    @endif
                </div>
                <!-- info input -->
                <div class="form-group">
                    {!! Form::text('info', null,['class' => 'form-control', 'id' => 'info', 'placeholder' => 'Info', 'required']) !!}
                    @if ($errors->has('info'))
                        <script>formErrors.push("info");</script>
                        <span class="help-block validation-error-label" for="info">{!! $errors->first('info') !!}</span>
                    @endif
                </div>


                {!! Form::submit('Add To Whitelist', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Ip Address</th>
                            <th>Information</th>
                            <th>Listed On</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($whitelisted as $whitelist)

                            <tr>
                                <td>{{ $whitelist->ip_address }}</td>
                                <td>{{ $whitelist->info }}</td>
                                <td>{{ $whitelist->created_at->format('dS M Y H:i:s') }}</td>
                                <td>
                                    {!! Form::open(['url' => 'admin/firewall/whitelist/remove']) !!}
                                    {!! Form::hidden('id', $whitelist->id) !!}
                                    {!! Form::button('<i class="far fa-times"></i> Remove', array('type' => 'submit', 'class' => 'btn btn-danger openbutton')) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div id="pagination-wrap">
                        <div class="pagination-info">Showing {!! $whitelisted->count() !!} out of {!! $whitelisted->total() !!} entries</div>
                        <div class="pagination-links">{!! $whitelisted->render() !!}</div>
                    </div>

                </div>
            </div>
@stop