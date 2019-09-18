<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Logs.php
 **/

namespace Quantum\base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Quantum\base\Models\Transactions;
use Quantum\base\Models\UserPurchase;
use Yajra\DataTables\Facades\DataTables;

class Logs extends Controller
{
    public function index()
    {
        return view('base::admin.Commerce.Logs.index');
    }

    public function data($user = null)
    {
        $sitecountry = \Countries::siteCountry();
        $date = \Carbon\Carbon::today()->subDays(90);

        $transactions = Transactions::with('payment_gateway', 'user')->where('created_at', '>=', $date)->orderBy('id', 'DESC')->get();


        return Datatables::of($transactions)
            ->editColumn('created_at', function ($model) {
                return [
                    'display' => e(
                        $model->created_at->diffForHumans()
                    ),
                    'timestamp' => $model->created_at->timestamp
                ];
            })
            //->editColumn('type', '{!! ucfirst($type) !!}')
            ->editColumn('type', function ($model) {
                return ucfirst($model->type);
            })
            //->editColumn('amount', $sitecountry->currency_symbol.'{!! $amount !!}')
            ->editColumn('amount', function ($model) use($sitecountry){
                return $sitecountry->currency_symbol.$model->amount;
            })
            ->editColumn('user.username', function ($model) {
                if(!$model->user) return '';
                return '<a target="_blank" href="'.url("/admin/user/".$model->user->username."/edit").'">'.$model->user->username.'</a>';
            })
            ->rawColumns(['user.username'])
            ->make(true);
    }

    public function dataUser($user)
    {
        $sitecountry = \Countries::siteCountry();
        $date = \Carbon\Carbon::today()->subDays(90);

        $transactions = Transactions::with('payment_gateway')->where('user_id', $user)->where('type', 'sale')->where('created_at', '>=', $date)->orderBy('id', 'DESC')->get();


        return Datatables::of($transactions)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            //->editColumn('type', '{!! ucfirst($type) !!}')
            ->editColumn('type', function ($model) {
                return ucfirst($model->type);
            })
            //->editColumn('amount', $sitecountry->currency_symbol.'{!! $amount !!}')
            ->editColumn('amount', function ($model) use($sitecountry){
                return $sitecountry->currency_symbol.$model->amount;
            })
            ->make(true);
    }
    
}