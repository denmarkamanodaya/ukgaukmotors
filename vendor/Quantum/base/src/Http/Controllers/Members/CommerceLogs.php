<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Logs.php
 **/

namespace Quantum\base\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Quantum\base\Models\Transactions;
use Yajra\DataTables\Facades\DataTables;

class CommerceLogs extends Controller
{

    public function dataUser()
    {
        $sitecountry = \Countries::siteCountry();
        $date = \Carbon\Carbon::today()->subDays(90);

        $transactions = Transactions::with(['payment_gateway' => function ($query) {
            $query->select('id','userTitle');

        }])->where('user_id', \Auth::user()->id)->where('type', 'sale')->where('created_at', '>=', $date)->orderBy('created_at', 'DESC')
            ->select('id', 'payment_gateway_id', 'trx_id', 'amount', 'created_at');


        return Datatables::eloquent($transactions)
            ->editColumn('type', function ($model) {
                return ucfirst($model);
            })
            //->editColumn('amount', $sitecountry->currency_symbol.'{!! $amount !!}')
            ->editColumn('amount', function ($model) use($sitecountry){
                return $sitecountry->currency_symbol.$model->amount;
            })
            ->editColumn('created_at', function ($model) {
                return [
                    'display' => e(
                        $model->created_at->toDayDateTimeString()
                    ),
                    'timestamp' => $model->created_at->timestamp
                ];
            })

            ->make(true);
    }


}