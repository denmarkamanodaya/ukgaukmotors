<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dealers;
use App\Services\VehicleService;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;


class Auctioneers extends Controller
{

    /**
     * @var VehicleService
     */
    private $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.Auctioneers.index');
    }

    public function data()
    {
        $auctioneers = \Cache::rememberForever('auctioneers', function() {
            return Dealers::withCount('calendarEvents')->orderBy('name', 'ASC')->get();
        });

        return Datatables::of($auctioneers)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('calendar_events_count', function ($model) {
                if($model->calendar_events_count > 0) return '<i class="fas fa-check fa-lg"></i>';
                return '';
            })
            ->addColumn('action', function ($auctioneer) {
                return '<a href="'.url('admin/dealers/auctioneer/'.$auctioneer->slug).'" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="fas fa-gavel"></i></b> Details</a>';
            })
            ->addColumn('logo', function ($auctioneer) {
                return '<img src="'.url('images/dealers/'.$auctioneer->id.'/thumb50-'.$auctioneer->logo).'">';
            })
            ->rawColumns(['logo', 'action', 'calendar_events_count'])

            ->make(true);
    }
    
    public function show($id)
    {
        $auctioneer = Dealers::where('slug', $id)->firstOrFail();
        return view('admin.Auctioneers.show', compact('auctioneer'));
    }
    
    public function vehicles($id)
    {
        $auctioneer = Dealers::where('slug', $id)->firstOrFail();
        $vehicles = $this->vehicleService->showAuctioneer($auctioneer->id);
        return view('admin.Vehicles.Auctioneers.index', compact('vehicles', 'auctioneer'));
    }

    public function vehicle($auctioneer, $id)
    {
        $auctioneer = Dealers::where('slug', $auctioneer)->firstOrFail();
        $vehicle = $this->vehicleService->show($id);
        return view('admin.Vehicles.Auctioneers.show', compact('vehicle', 'auctioneer'));

    }

    public function vehiclesDelete($id)
    {
        $auctioneer = Dealers::where('slug', $id)->firstOrFail();
        $this->vehicleService->deletefromAuctioneer($auctioneer->id);
        return view('admin.Auctioneers.show', compact('auctioneer'));
    }

    public function edit($id)
    {
	    $auctioneer = Dealers::where('slug', $id)->firstOrFail();
        return view('admin.Auctioneers.edit', compact('auctioneer'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->address, $id);
        // $calendarService = new CalendarService();
        // $calendarService->updateEvent($request, $event);
        // \Cache::forget('auctioneers');
        Dealers::where('slug', $id)->update(array(
            'address'       => $request->address,
            'town'          => $request->town,
            'postcode'      => $request->postcode,
            'county'        => $request->county,
            'longitude'     => $request->longitude,
            'latitude'      => $request->latitude,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'website'       => $request->website,
            'auction_url'   => $request->auction_url,
            'online_bidding_url' => $request->online_bidding_url,
            'details'       => $request->details,
        ));
        flash('Dealer has been updated')->success();
        return redirect('/admin/dealers/auctioneer/'.$id.'/edit');
    }
}
