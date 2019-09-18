@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this membership?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/membership')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Membership' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Membership</span> - Manage site membership
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Membership Types</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row mb-20">
                        <div class="col-md-6">

                                <a href="{{url('/admin/membership/create')}}">
                                    <button class="btn bg-teal-400 btn-labeled" type="button">
                                        <b>
                                            <i class="far fa-file-plus"></i>
                                        </b>
                                        Create New Membership
                                    </button>
                                </a>
                        </div>

                        <div class="col-md-6 text-right">
                                <a href="{{url('/admin/membership/settings/registration-form')}}">
                                    <button class="btn bg-teal-400 btn-labeled" type="button">
                                        <b>
                                            <i class="far fa-list-ul"></i>
                                        </b>
                                        Registration Form Settings
                                    </button>
                                </a>
                        </div>

                    </div>



                    <div class="row">
                        <div class="col-md-12">

                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/membership/', 'class' => 'form-horizontal')) !!}

                            <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th class="text-center">Expires</th>
                                    <th class="text-center">Subscription</th>
                                    <th>Period</th>
                                    <th class="text-center">Default</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($membershipTypes as $membership)
                                    <tr>
                                        <td>{{$membership->id}}</td>
                                        <td>{{$membership->title}}</td>
                                        <td>{{$membership->slug}}</td>
                                        <td>{{ucfirst($membership->type)}}</td>
                                        <td>{{Countries::siteCountry()->currency_symbol}} {{$membership->amount}}</td>
                                        <td class="text-center">
                                            @if($membership->expires == 1)
                                                <i class="far fa-check"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($membership->subscription == 1)
                                                <i class="far fa-check"></i>
                                            @endif
                                        </td>
                                        <td>@if($membership->expires == 1)
                                                {{$membership->subscription_period_amount}} {{$membership->subscription_period_type}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($membership->register_default == 1)
                                                <i class="far fa-check"></i>
                                            @endif
                                        </td>
                                        <td>{{ucfirst($membership->status)}}</td>
                                        <td>{{$membership->created_at->format('d/m/y h:i:s')}}</td>
                                        <td>{{$membership->updated_at->diffForHumans()}}</td>
                                        <td>
                                            <a href="{{url('admin/membership/'.$membership->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                            &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$membership->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>




                </div>
            </div>

        </div>

    </div>

@stop


