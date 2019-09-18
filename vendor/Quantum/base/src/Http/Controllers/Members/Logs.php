<?php

namespace Quantum\base\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Quantum\base\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;


class Logs extends Controller
{

    public function data()
    {
            $userActivity = ActivityLog::where('user_id',\Auth::user()->id)->orderBy('created_at', 'DESC')
        ->select('id', 'text', 'created_at');

        return Datatables::eloquent($userActivity)
            ->editColumn('created_at', function ($model) {
                return [
                    'display' => e(
                        $model->created_at->diffForHumans()
                    ),
                    'timestamp' => $model->created_at->timestamp
                ];
            })
            ->make(true);
    }


}
