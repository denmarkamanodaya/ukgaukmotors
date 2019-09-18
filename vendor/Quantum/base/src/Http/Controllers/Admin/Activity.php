<?php

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\ActivityLog;
use Quantum\base\Services\ActivityLogService;
use Yajra\DataTables\Facades\DataTables;


class Activity extends Controller
{

    /**
     * @var ActivityLogService
     */
    private $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     //$userActivity = ActivityLog::with('user')->orderBy('created_at', 'DESC')->paginate(50);
        return view('base::admin.Activity.index');
    }

    public function data($user = null)
    {
        if($user)
        {
            $userActivity = ActivityLog::with('user')->where('user_id',$user)->whereNotNull('user_id')->orderBy('created_at', 'DESC');
        } else {
            $userActivity = ActivityLog::with('user')->whereNotNull('user_id')->orderBy('created_at', 'DESC');
        }


        return Datatables::eloquent($userActivity)
            ->editColumn('created_at', function ($model) {
                return [
                    'display' => e(
                        $model->created_at->diffForHumans()
                    ),
                    'timestamp' => $model->created_at->timestamp
                ];
            })
            ->editColumn('user.username', function ($model) {
                return isset($model->user) ? $model->user->username : '';
            })
            ->make(true);
    }


}
