@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
    <style>
        .iconList {
            padding-left: 0;
            list-style: none;
        }

        .iconList li {
            float: left;
            width: 10%;
            height: 115px;
            padding: 10px;
            font-size: 32px;
            line-height: 1.4;
            text-align: center;
            background-color: #f9f9f9;
            border: 1px solid #fff;
        }
        .iconList li span {
            font-size: 12px;
        }
    </style>
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Emails' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Icons</span> - View System Icons
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">System Icons</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    @foreach($faicons as $category => $icons)

                        <div class="row">

                            <h2>{{ucfirst($category)}}</h2>
                            <ul class="iconList">
                                @foreach($icons as $icon)
                                    <li><i class="{{$icon}}"></i><br><span class="iconName">{{$icon}}</span> </li>
                                @endforeach
                            </ul>
                        </div>


                    @endforeach

                </div>
            </div>

        </div>

    </div>

@stop


