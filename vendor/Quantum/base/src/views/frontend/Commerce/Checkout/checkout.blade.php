<div class="row">
    <div class="col-md-8">


                @foreach($cartItems as $item)
                    <?php $summary = $item->product->summary; ?>
                    <div class="panel panel-default border-grey">
                        <div class="panel-heading">
                            <h6 class="panel-title">{{ucfirst($item->type)}} - {{$item->name}}</h6>
                            <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                        <div class="panel-body">
                            {!!$item->product->description!!}
                        </div>
                    </div>
                @endforeach

    </div>

    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default border-grey">
                    <div class="panel-heading">
                        <h6 class="panel-title">Order Summary</h6>
                        <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-8 text-right">Subtotal</div>
                            <div class="col-md-4">{{$sitecountry->currency_symbol}}{{$subtotal}}</div>
                        </div>

                        <div class="row">
                            <hr />
                        </div>

                        <div class="row">
                            <div class="col-md-8 text-right"><b>Total</b></div>
                            <div class="col-md-4">{{$sitecountry->currency_symbol}}{{$total}}</div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">{{$summary}}</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default border-grey">
                    <div class="panel-heading">
                        <h6 class="panel-title">Select Payment</h6>
                        <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                    </div>
                    <div class="panel-body text-center">
                        @foreach($payment_buttons as $button)

                            <div class="payment_button">
                                <a href="{{$button['url']}}"><img src="{{$button['image']}}"></a>
                            </div>

                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>


</div>

