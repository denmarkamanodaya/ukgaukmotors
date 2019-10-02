@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
	$(function() {
		$(".table tbody tr").click(function(){
		//add the css class which has the required background color property to the clicked table row
		$(this).css("background-color", "#00FFFF");
		});
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Parsing' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Parsing - Parsed Data</span>
@stop


@section('content')

    <div class="row">
	<div class="col-md-12">

                <div class="panel-body">
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="auctioneers-table">
                        <thead>
			<tr>
			    	<th class="col-md-1">ID</th>
				<th class="col-md-5">Name</th>
                            	<th class="col-md-4">Slug</th>
                            	<th class="col-md-2">Count</th>
                        </tr>
			</thead>


			<tbody>
				@foreach ($parsed_data AS $value)
					<tr>
						<td>{{ $value->id }}</td>
						<td>{{ $value->name }}</td>
						<td>{{ $value->slug }}</td>
						<td>{{ $value->total }}</td>
					</tr>
				@endforeach
			</tbody>
                    </table>
                </div>

	</div>
    </div>
@stop


