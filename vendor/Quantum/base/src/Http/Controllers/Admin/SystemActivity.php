<?php

namespace Quantum\base\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\ActivityLog;
use Quantum\base\Services\ActivityLogService;
use Yajra\DataTables\Facades\DataTables;


class SystemActivity extends Controller
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
        return view('base::admin.Activity.systemIndex');
    }

    public function data()
    {

            $systemActivity = ActivityLog::whereNull('user_id')->tenant()->orderBy('created_at', 'DESC')
                    ->whereDate('created_at', '>=', Carbon::today()->subweek(1)->toDateString());

        return Datatables::of($systemActivity)
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
