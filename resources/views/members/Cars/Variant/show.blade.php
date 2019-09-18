@extends('base::members.Template')


@section('page_title', 'Car Make')
@section('body-class', 'single-post woocommerce woocommerce-page single single-product')

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/search.js')}}" type="text/javascript"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Motorpedia' => '/members/motorpedia', $carMake->name => '/members/motorpedia/car-make/'.$carMake->slug, $carModel->name => '/members/motorpedia/car-make/'.$carMake->slug.'/'.$carModel->slug.'#modelVariants', 'Variant Specification' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop

@section('pre-content')
    <div class="page-section page-intro page-intro-bc"><img class="img-responsive" src="{!! url('/images/big_data_hero.png') !!}" /></div>
    @include('members.Cars.partials.car_data_searchbar')
    <div class="cs-subheader">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        @if($carMake->logo != '')
                            <div class="col-md-1">
                                <div class="make_logo_header">
                                    {!! show_make_logo($carMake, 50) !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-11">
                            @if($carMake->country)
                                <div class="make_flag_img"><img src="{!! url('/images/flags/'.$carMake->country->flag) !!}"></div>
                            @endif
                            <div class="cs-subheader-text make_name_header">
                                <h2>{{ $carMake->name }} {{ $carModel->name }} : {{ $carVariant->model_desc }}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                @if($carVariant->description)
                    @if($carVariant->description->featured_image)
                        <ul class="blog-detail-slider" style="margin-bottom:30px;">
                            <li>
                                <figure><img src="{{ featured_image($carVariant->description->featured_image) }}" alt="" /></figure>
                            </li>
                        </ul>
                    @endif
                @endif

                <div class="woocommerce-tabs wc-tabs-wrapper car-data-tab-adjust">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs wc-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#specifications" aria-controls="home" role="tab" data-toggle="tab">Technical Specifications</a>
                        </li>
                        <li role="presentation">
                            <a href="#description" aria-controls="home" role="tab" data-toggle="tab">Description</a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane" id="description">
                            @if($carVariant->description)
                                <div class="cs-blog-detail-text">
                                @if($carVariant->description->content != '')
                                    {!! $carVariant->description->content !!}
                                    @else
                                        We are sorry this variants description has not yet been completed.<br>Please check back soon as we are constantly updating our data.
                                    @endif
                                </div>
                            @else
                                We are sorry this variants description has not yet been completed.<br>Please check back soon as we are constantly updating our data.
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="specifications">
                            <div class="car_spec_list">
                                <ul class="row">
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <i class="cs-color icon-calendar3"></i>
                                            <span>General Details</span>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Year Sold</span>
                                                    <strong>{{ $carVariant->year_sold }}</strong>
                                                </li>
                                                <li>
                                                    <span>Location</span>
                                                    <strong>{{ $carVariant->location }}</strong>
                                                </li>
                                                <li>
                                                    <span>Classification</span>
                                                    <strong>{{ $carVariant->classification }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">

                                        </div>
                                    </li>
                                </ul>




                                <ul class="row">
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <i class="cs-color icon-car36"></i>
                                            <span>Body Data</span>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Body Type</span>
                                                    <strong>{{ $carVariant->body_type }}</strong>
                                                </li>
                                                <li>
                                                    <span>Number of Doors</span>
                                                    <strong>{{ $carVariant->doors }}</strong>
                                                </li>
                                                <li>
                                                    <span>Number of Seats</span>
                                                    <strong>{{ $carVariant->seats }}</strong>
                                                </li>
                                                <li>
                                                    <span>Engine Place</span>
                                                    <strong>{{ $carVariant->engine_place }}</strong>
                                                </li>
                                                <li>
                                                    <span>Drivetrain</span>
                                                    <strong>{{ $carVariant->drivetrain }}</strong>
                                                </li>
                                                <li>
                                                    <span>Length (mm)</span>
                                                    <strong>{{ $carVariant->length }}</strong>
                                                </li>
                                                <li>
                                                    <span>Width (mm)</span>
                                                    <strong>{{ $carVariant->width }}</strong>
                                                </li>
                                                <li>
                                                    <span>Height (mm)</span>
                                                    <strong>{{ $carVariant->height }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Wheel Base (mm)</span>
                                                    <strong>{{ $carVariant->wheel_base }}</strong>
                                                </li>
                                                <li>
                                                    <span>Track Front (mm)</span>
                                                    <strong>{{ $carVariant->track_front }}</strong>
                                                </li>
                                                <li>
                                                    <span>Track Rear (mm)</span>
                                                    <strong>{{ $carVariant->track_rear }}</strong>
                                                </li>
                                                <li>
                                                    <span>Curb Weight (kg)</span>
                                                    <strong>{{ $carVariant->curb_weight }}</strong>
                                                </li>
                                                <li>
                                                    <span>Gross Weight (kg)</span>
                                                    <strong>{{ $carVariant->gross_weight }}</strong>
                                                </li>
                                                <li>
                                                    <span>Cargo Space (litres)</span>
                                                    <strong>{{ $carVariant->cargo_space }}</strong>
                                                </li>
                                                <li>
                                                    <span>Tow Weight (kg)</span>
                                                    <strong>{{ $carVariant->tow_weight }}</strong>
                                                </li>
                                                <li>
                                                    <span>Gas Tank (litres)</span>
                                                    <strong>{{ $carVariant->gas_tank }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>


                                <ul class="row">
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <i class="cs-color icon-engine"></i>
                                            <span>Engine Data</span>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Cylinders</span>
                                                    <strong>{{ $carVariant->cylinders }}</strong>
                                                </li>
                                                <li>
                                                    <span>Displacement (cm&sup3;)</span>
                                                    <strong>{{ $carVariant->displacement }}</strong>
                                                </li>
                                                <li>
                                                    <span>Power (ps)</span>
                                                    <strong>{{ $carVariant->power_ps }}</strong>
                                                </li>
                                                <li>
                                                    <span>Power (kw)</span>
                                                    <strong>{{ $carVariant->power_kw }}</strong>
                                                </li>
                                                <li>
                                                    <span>Power (rpm)</span>
                                                    <strong>{{ $carVariant->power_rpm }}</strong>
                                                </li>
                                                <li>
                                                    <span>Torque (Nm)</span>
                                                    <strong>{{ $carVariant->torque_nm }}</strong>
                                                </li>
                                                <li>
                                                    <span>Torque (rpm)</span>
                                                    <strong>{{ $carVariant->torque_rpm }}</strong>
                                                </li>
                                                <li>
                                                    <span>Bore X Stroke (mm)</span>
                                                    <strong>{{ $carVariant->bore_stroke }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Compression Ratio</span>
                                                    <strong>{{ $carVariant->compression_ration }}</strong>
                                                </li>
                                                <li>
                                                    <span>Valves per</span>
                                                    <strong>{{ $carVariant->valves_cylinder }}</strong>
                                                </li>
                                                <li>
                                                    <span>Crankshaft</span>
                                                    <strong>{{ $carVariant->crankshaft }}</strong>
                                                </li>
                                                <li>
                                                    <span>Fuel Injection</span>
                                                    <strong>{{ $carVariant->fuel_injection }}</strong>
                                                </li>
                                                <li>
                                                    <span>Supercharger</span>
                                                    <strong>{{ $carVariant->supercharged }}</strong>
                                                </li>
                                                <li>
                                                    <span>Catalytic</span>
                                                    <strong>{{ $carVariant->catalytic }}</strong>
                                                </li>
                                                <li>
                                                    <span>Manual</span>
                                                    <strong>{{ $carVariant->manual }}</strong>
                                                </li>
                                                <li>
                                                    <span>Automatic</span>
                                                    <strong>{{ $carVariant->automatic }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>


                                <ul class="row">
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <i class="cs-color icon-gears2"></i>
                                            <span>Drivetrain Data</span>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Suspension Front</span>
                                                    <strong>{{ $carVariant->suspension_front }}</strong>
                                                </li>
                                                <li>
                                                    <span>Suspension Rear</span>
                                                    <strong>{{ $carVariant->suspension_rear }}</strong>
                                                </li>
                                                <li>
                                                    <span>Assisted Stearing</span>
                                                    <strong>{{ $carVariant->assisted_steering }}</strong>
                                                </li>
                                                <li>
                                                    <span>Brakes Front</span>
                                                    <strong>{{ $carVariant->brakes_front }}</strong>
                                                </li>
                                                <li>
                                                    <span>Brakes Rear</span>
                                                    <strong>{{ $carVariant->brakes_rear }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>ABS</span>
                                                    <strong>{{ $carVariant->abs }}</strong>
                                                </li>
                                                <li>
                                                    <span>ESP</span>
                                                    <strong>{{ $carVariant->esp }}</strong>
                                                </li>
                                                <li>
                                                    <span>Tire Size</span>
                                                    <strong>{{ $carVariant->tire_size }}</strong>
                                                </li>
                                                <li>
                                                    <span>Tire Size Rear (If different)</span>
                                                    <strong>{{ $carVariant->tire_size_rear }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>



                                <ul class="row">
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <i class="cs-color icon-vehicle92"></i>
                                            <span>Performance Data</span>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>0 - 100kmph (sec)</span>
                                                    <strong>{{ $carVariant->zero_hundred }}</strong>
                                                </li>
                                                <li>
                                                    <span>Max Speed (kmh)</span>
                                                    <strong>{{ $carVariant->max_speed }}</strong>
                                                </li>
                                                <li>
                                                    <span>Fuel Efficiency (l/100km)</span>
                                                    <strong>{{ $carVariant->fuel_eff }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="specifications-info">
                                            <ul>
                                                <li>
                                                    <span>Engine Type</span>
                                                    <strong>{{ $carVariant->engine_type }}</strong>
                                                </li>
                                                <li>
                                                    <span>Fuel Type</span>
                                                    <strong>{{ $carVariant->fuel_type }}</strong>
                                                </li>
                                                <li>
                                                    <span>CO2</span>
                                                    <strong>{{ $carVariant->co2 }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">
            @include('Shortcodes.relatedMakeWidget')
            {!! Shortcode::parse('[vehicleLatestWidget amount="4"]') !!}
            {!! Shortcode::parse('[vehicleLatestWidget amount="4" type="classified"]') !!}
            {!! Shortcode::parse('[vehicleEndingSoonWidget amount="4"]') !!}

            <div class="widget widget-recent-posts">
                <h6>Recent Posts</h6>
                <ul>
                    @foreach($latestPosts as $post)
                        <li>

                            <div class="cs-media">
                                @if($post->meta->featured_image)
                                    <figure>
                                        <a href="{!! postLink($post, 'members') !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a>
                                    </figure>
                                @endif
                            </div>
                            <div class="cs-text">
                                <a href="{!! postLink($post, 'members') !!}">{{ $post->title }}</a>
                                <span><i class="icon-clock5"></i>{{ $post->publish_on->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{!! url('/members/posts') !!}" class="cs-view-blog">View all Blogs</a>
            </div>

        </aside>
    </div>


@stop


