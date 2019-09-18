@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/jquery_ui/full.min.js')}}"></script>
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            var chapter = $(this).data('chapter');
            var page = $(this).data('page');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this page?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{ url('admin/book')}}/' + id + '/chapter/' + chapter + '/page/' + page + '/delete';
                    }
                }
            });
        });

        $("#book-pages-table").sortable({
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Books' => '/admin/books', 'Book Chapters' => '/admin/book/'.$book->id.'/manage', 'Chapter' => '/admin/book/'.$book->id.'/chapter/'.$chapter->id.'/pages','Chapter Pages' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Book</span> - Manage your Chapter's Pages
@stop


@section('content')
    <div class="row">
        <div class="col-md-12 text-center mb-20">
            @if($book->front_cover != '')
                <img src="{!! url($book->front_cover) !!}" id="" class="" style="max-height:200px;">
            @endif
            @if($book->back_cover != '')
                <img src="{!! url($book->back_cover) !!}" id="" class="ml-10" style="max-height:200px;">
            @endif
        </div>
        <div class="col-md-12 text-center mb-20"><h3>{{$chapter->title}}</h3></div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Chapter's Pages</h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                            <li><a data-action="reload"></a></li>
                            <li><a data-action="close"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{ url('/admin/book/'.$book->id.'/chapter/'.$chapter->id.'/createPage')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="far fa-book"></i>
                            </b>
                            Create New Page
                        </button>
                    </a>

                    {!! Form::open(array('method' => 'POST', 'url' => 'admin/book/'.$book->id.'/chapter/'.$chapter->id.'/pages/savePosition')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="book-pages-table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($chapter->pages as $page)
                            <tr>
                                {!! Form::hidden('position[]', $page->id) !!}
                                <td>{{$page->title}}</td>
                                <td>{{$page->created_at->format('d/m/y h:i:s')}}</td>
                                <td>{{$page->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ url('admin/book/'.$book->id.'/chapter/'.$chapter->id.'/page/'.$page->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Page</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$book->id}}' data-chapter='{{$chapter->id}}' data-page='{{$page->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
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


