@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/jquery_ui/full.min.js')}}"></script>
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this responder?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/newsletter/'.$newsletter->slug.'/responder')}}/' + id + '/delete';
                    }
                }
            });
        });

        $("#responder-table").sortable({
            items: "tbody > tr",
            cursor: 'move',
            opacity: 0.6,
            placeholder: "ui-state-highlight",
            update: function() {
            }
        });


    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', $newsletter->title => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Manage Newsletter Responders
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Responders for {{$newsletter->title}}</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/newsletter/'.$newsletter->slug.'/responder/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-retweet"></i>
                            </b>
                            Create A Responder Message
                        </button>
                    </a><br><br>
                    <p>The message send delay determines when the message is sent after the previous one in the list. The delay on the first message in this list will be after the subscriber joins.<br>The message order can be changed by dragging and dropping each message in the order you desire, then hitting the save position button.</p>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/'.$newsletter->slug.'/updateResponderPositions')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="responder-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Subject</th>
                            <th>Send Delay</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($newsletter->responders) > 0)
                            @foreach($newsletter->responders as $responder)
                                <tr>{!! Form::hidden('position[]', $responder->id) !!}
                                    <td>{{$responder->id}}</td>
                                    <td>{{$responder->subject}}</td>
                                    <td>{{$responder->interval_amount}} {{$responder->interval_type}}</td>
                                    <td>{{$responder->updated_at->diffForHumans()}}</td>
                                    <td>
                                        <a href="{{url('admin/newsletter/'.$newsletter->slug.'/responder/'.$responder->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                        &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="confirmDelete" data-id='{{$responder->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="mt-20">
                        <button type="submit" class="btn btn-primary">Save Positions <i class="far fa-save position-right"></i></button>
                    </div>
                    {!! Form::close() !!}



                </div>
            </div>

        </div>

    </div>

@stop


