<?php

namespace Quantum\base\Http\Controllers\Members;



use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartTest2 extends Controller
{



    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $payment = \Payment::setGateway();
       // $payment->addItem('Test', '4.99');
       //  return $payment->purchaseItems();
       // dd($payment->test());


        dd(\Cart::getItems());

    }


}
