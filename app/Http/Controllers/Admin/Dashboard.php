<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dealers;
use App\Models\VehicleCountLog;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelVarient;
use App\Models\Vehicles;
use Cache;
use Carbon\Carbon;
use DB;
use Quantum\base\Models\News;
use Quantum\newsletter\Models\Newsletter;
use Quantum\base\Models\Page;
use Quantum\base\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\tickets\Models\Tickets;
use Quantum\tickets\Models\TicketStatus;

class Dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::Area('admin')->Published()->latest()->tenant()->get();
        $totals['users'] = User::count();
        $totals['pages'] = Page::tenant()->count();
        $totals['news'] = News::tenant()->count();
        $totals['auctioneers'] = Dealers::where('type', 'auctioneer')->count();
        $totals['vehicles'] = Vehicles::count();
        $totals['vehicleMakes'] = VehicleMake::count();
        $totals['vehicleModels'] = VehicleModel::count();
        $totals['vehicleVariants'] = VehicleModelVarient::count();

        $newsletters = $this->newslettersWidget();

        return view('admin.dashboard', compact('news', 'totals', 'newsletters'));
    }

    public function userWidget()
    {
        $userCount['admin'] = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();
        $userCount['members'] = User::whereHas('roles', function ($query) {
            $query->where('name', 'member');
        })->count();
        $userCount['premium'] = User::whereHas('roles', function ($query) {
            $query->where('name', 'premium');
        })->count();
        $userCount['premium-auctions'] = User::whereHas('roles', function ($query) {
            $query->where('name', 'premium-auctions');
        })->count();

        $userCount['total'] = $userCount['members'];
        $userCount['members'] = $userCount['members'] - $userCount['premium'] - $userCount['premium-auctions'];

        $registeredDates = $this->userRegisterWidget();

        $view = \View::make('admin.Dashboard.users', compact('userCount', 'registeredDates'));
        echo $view->render();
        exit;

    }

    public function userRegisterWidget()
    {
        $yesterday = Carbon::now()->subDays(0);
        $one_week_ago = Carbon::now()->subWeeks(1);

        $importDates = Cache::remember('dashUserRegisterDates', 10, function () use($yesterday, $one_week_ago) {
            return User::select( DB::raw('DATE_FORMAT(created_at, "%d") as `date`'),DB::raw('COUNT(*) as `count`'))
                ->where('created_at', '>=', $one_week_ago)
                ->where('created_at', '<=', $yesterday)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->pluck('count', 'date');
        });

        $registeredDates = $importDates->reverse();
        return $registeredDates;
        //$view = \View::make('admin.Dashboard.vehicleImportCount', compact('registeredDates'));
        //echo $view->render();
    }

    public function ticketWidget()
    {
        $tickets = [];
        $statuses = TicketStatus::all();
        $openStatus = '';
        $closedStatus = '';
        $otherStatus = [];
        $allStatuses = [];
        foreach ($statuses as $status)
        {
            if($status->slug == 'open')
            {
                $tickets['open'] = Tickets::where('ticket_status_id', $status->id)->count();
                array_push($allStatuses, $status->id);
            } elseif($status->slug == 'closed') {
                $tickets['closed'] = Tickets::where('ticket_status_id', $status->id)->count();
            } elseif($status->slug == 'awaiting_reply') {
                $tickets['awaiting_reply'] = Tickets::where('ticket_status_id', $status->id)->count();
                array_push($allStatuses, $status->id);
            } elseif($status->slug == 'user_replied') {
                $tickets['user_replied'] = Tickets::where('ticket_status_id', $status->id)->count();
                array_push($allStatuses, $status->id);
            } elseif($status->slug == 'staff_replied') {
                $tickets['staff_replied'] = Tickets::where('ticket_status_id', $status->id)->count();
                array_push($allStatuses, $status->id);
            } else {
                array_push($otherStatus, $status->id);
                array_push($allStatuses, $status->id);
            }
        }

        $tickets['total'] = Tickets::whereIn('ticket_status_id', $allStatuses)->count();
        $tickets['replied'] = Tickets::whereIn('ticket_status_id', $otherStatus)->count();
        $tickets['latest'] = Tickets::whereIn('ticket_status_id', $allStatuses)->orderBy('updated_at', 'DESC')->limit(5)->get();
        $view = \View::make('admin.Dashboard.tickets', compact('tickets'));
        echo $view->render();
        exit;
    }

    public function vehicleCountWidget()
    {

        $vehicleCount = Cache::remember('dashImportVehicles', 10, function () {
            return VehicleCountLog::orderBy('created_at', 'DESC')->limit(7)->get();
        });

        $vehicleCount = $vehicleCount->reverse();
        $view = \View::make('admin.Dashboard.vehicleCount', compact('vehicleCount'));
        echo $view->render();
    }

    public function vehicleImportAmountWidget()
    {

        $yesterday = Carbon::now()->subDays(0);
        $one_week_ago = Carbon::now()->subWeeks(1);

        $importDates = Cache::remember('dashImportDates', 10, function () use($yesterday, $one_week_ago) {
            return Vehicles::select( DB::raw('DATE_FORMAT(created_at, "%d") as `date`'),DB::raw('COUNT(*) as `count`'))
                ->where('created_at', '>=', $one_week_ago)
                ->where('created_at', '<=', $yesterday)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->pluck('count', 'date');
        });

        $importDates = $importDates->reverse();
        $view = \View::make('admin.Dashboard.vehicleImportCount', compact('importDates'));
        echo $view->render();
    }

    public function newslettersWidget()
    {
        $newsletters = Newsletter::withCount('subscribers')->orderBy('title', 'ASC')->get();
        return $newsletters;
    }

}
