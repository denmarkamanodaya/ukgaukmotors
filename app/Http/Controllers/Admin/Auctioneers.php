<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dealers;
use App\Services\VehicleService;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\Postcode;
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
	  /*
        $auctioneers = \Cache::rememberForever('auctioneers', function() {
		return Dealers::withCount('calendarEvents')->orderBy('name', 'ASC')->get();
	});
	*/

	$auctioneers = Dealers::where('status', '=', 'approved')->orderBy('name', 'ASC')->get();

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

	public function create()
	{
		/*
	        $categories = \App\Models\DealerCategories::whereHas('children')->orderBy('name', 'ASC')->get();
	        $features = DealersFeatures::orderBy('name', 'ASC')->get();
        	$countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
 	        return view('admin.Auctioneers2.create', compact('categories', 'features', 'countries'));
		*/
		return view('admin.Auctioneers.create');
	}

    	public function saveCreate(Request $request)
	{
		// Generate slug
		$slug = strtolower(str_replace(' ', '-', trim($request['name'])));

		// Check if slug already exists
		$check = Dealers::where('slug', '=', $slug)->first();
		
		// Generate Lat/Lang from Postcode
		if($request['postcode'] != '')
        	{
            		if($geo_location = Postcode::postcode($request['postcode'])->first())
            		{
	                	$longitude  = $geo_location->longitude;
	        	        $latitude   = $geo_location->latitude;
            		}
        	}

		if(is_null($check))
		{
			// Main insert query
		        $dealer = new Dealers();
	        	$dealer->name = trim($request['name']);
			$dealer->slug = $slug;
			$dealer->address = trim($request['address']);
			$dealer->town = trim($request['town']);
			$dealer->postcode = trim($request['postcode']);
			$dealer->county = trim($request['county']);
			$dealer->longitude = isset($longitude) ? $longitude : '';
			$dealer->latitude = isset($latitude) ? $latitude : '';
			$dealer->phone = trim($request['phone']);
			$dealer->email = trim($request['email']);
			$dealer->website = trim($request['website']);
			$dealer->auction_url = isset($request['auction_url']) ? trim($request['auction_url']) : '';
			$dealer->online_bidding_url = isset($request['online_bidding_url']) ? trim($request['online_bidding_url']) : '';
			$dealer->details = isset($request['details']) ? $request['details'] : '';
			$dealer->type = 'auctioneer';
			$dealer->status = 'approved';
			$dealer->save();

			$dealer->logo = $this->logoPicture($dealer,$request);
		        $dealer->save();

			flash('Dealer has been created')->success();
		}
		else
		{
			flash('Dealer already exists')->error();
		}

		return view('admin.Auctioneers.index');
	}

    public function deleteDealer(Request $request)
    {
	$slug = $request['slug'];

	if($slug)
	{
        	$dealer = $this->getDealerBySlug($slug);
		$path = rtrim(dealer_logo_path($dealer->id), '/');
		// $dealer->media()->delete();
	        array_map('unlink', glob("$path/*.*"));
	        File::delete($path);
		
		$dealer->delete();

		// Delete all vehicle as well
		$auctioneer = Dealers::where('slug', $slug)->firstOrFail();
	        $this->vehicleService->deletefromAuctioneer($auctioneer->id);

		// Add to deleted dealers log table (but non-existent)
		#DeletedDealers::create(['dealer_id' => $dealer->id]);
        	#\Activitylogger::log('Dealer Deleted : '.$dealer->name, $dealer);

		flash('Dealer has been deleted.')->success();
	}
	else
	{
		flash('Slug empty, please report to developer')->error();
	}

	return view('admin.Auctioneers.index');
    }

    public function getDealerBySlug($slug)
    {
        return Dealers::where('slug', $slug)->firstOrFail();
    }

    private function logoPicture($auctioneer, $request)
    {
        $logoPic = isset($request['logo'])? $request['logo'] : $auctioneer->logo;
        $path = dealer_logo_path($auctioneer->id);
        if($request['delPicture'])
        {
            $this->deleteLogoImages($path, $auctioneer);
            $logoPic = null;
        }

        if($request->file('logo'))
        {

            $logoPic = $request->file('logo')->getClientOriginalName();
            $logoPic = str_replace(' ', '_', $logoPic);

            $image = Image::make($request->file('logo')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            if($logoPic != '')
            {
                $this->deleteLogoImages($path, $auctioneer);
            }

            //Save new
            $image->save($path. $logoPic);

            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb300-'.$logoPic);

            $image->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb150-'.$logoPic);

            $image->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb100-'.$logoPic);

            $image->resize(50, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb50-'.$logoPic);

        }
        return $logoPic;
    }

    private function deleteLogoImages($path, $auctioneer)
    {
        //remove old images
        File::delete($path . $auctioneer->logo);
        File::delete($path .'/thumb300-'. $auctioneer->logo);
        File::delete($path .'/thumb150-'. $auctioneer->logo);
        File::delete($path .'/thumb100-'. $auctioneer->logo);
        File::delete($path .'/thumb50-'. $auctioneer->logo);
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
