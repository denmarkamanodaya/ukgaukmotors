<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Cart.php
 **/

namespace Quantum\base\Http\Controllers\Members;

use App\Http\Controllers\Controller;

class Cart extends Controller
{

    public function add($type, $modelId)
    {
        $add = \Cart::addFromModel($type, $modelId);
        if(!$add) abort(404);
        return $add;
    }
}