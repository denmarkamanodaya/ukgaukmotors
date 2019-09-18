<?php

namespace Quantum\base\Http\Controllers\Members;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\MembershipTypes;

class CartTest extends Controller
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
        $Membership = MembershipTypes::where('type', 'paid')->tenant()->first();

        \Cart::clear();

        $added = \Cart::add([
            'id'       => 'Membership-'.$Membership->title,
            'name'     => $Membership->title,
            'quantity' => 1,
            'price'    => $Membership->amount,
            'type'    => 'membership',
            'subscription' => true,
            'model_id'    => $Membership->id,
            'model' => \Quantum\base\Models\MembershipTypes::class
        ]);

return $added;
    }


}
