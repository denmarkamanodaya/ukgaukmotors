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
                message: 'Are you sure you want to delete this book?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{ url('admin/book')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Books' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Book</span> - Manage your books
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Books</h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                            <li><a data-action="reload"></a></li>
                            <li><a data-action="close"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{ url('/admin/book/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="far fa-book"></i>
                            </b>
                            Create New Book
                        </button>
                    </a>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Front</th>
                            <th>Rear</th>
                            <th>Featured</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td>{{$book->title}}</td>
                                <td>
                                    @if($book->front_cover != '')
                                        <i class="far fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($book->back_cover != '')
                                        <i class="far fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($book->meta && $book->meta->featured_image != '')
                                        <i class="far fa-check"></i>
                                    @endif
                                </td>
                                <td>{{$book->created_at->format('d/m/y h:i:s')}}</td>
                                <td>{{$book->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ url('admin/book/'.$book->id.'/manage')}}" class="btn btn-info btn-labeled" type="button"><b><i class="far fa-book"></i></b> Manage Chapters</a>
                                    <a href="{{ url('admin/book/'.$book->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Details</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$book->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $books->count() !!} out of {!! $books->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $books->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


