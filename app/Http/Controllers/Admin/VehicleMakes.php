<?php

namespace App\Http\Controllers\Admin;

use App\Models\VehicleMakeDescription;
use App\Services\WikiScraperService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\VehicleTypeService;
use Quantum\base\Models\Countries;
use Yajra\DataTables\Facades\DataTables;


class VehicleMakes extends Controller
{

    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;
    /**
     * @var WikiScraperService
     */
    private $wikiScraperService;

    public function __construct(VehicleTypeService $vehicleTypeService, WikiScraperService $wikiScraperService)
    {
        $this->vehicleTypeService = $vehicleTypeService;
        $this->wikiScraperService = $wikiScraperService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.VehicleMakes.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicleMake = \App\Models\VehicleMake::where('slug', $id)->firstOrFail();
        $countrylist = Countries::pluck('name', 'id');
        return view('admin.VehicleMakes.edit', compact('vehicleMake', 'countrylist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\EditVehicleMakeRequest $request, $id)
    {
        $this->vehicleTypeService->editMake($request, $id);
        return redirect('admin/vehicle-makes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vehicleTypeService->deleteMake($id);
        return redirect('admin/vehicle-makes');
    }

    public function data()
    {
        $vehicleMakes = $this->vehicleTypeService->getCachedMakesFull();

        return Datatables::of($vehicleMakes)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('logo', function($vehicleMake) {
                return show_make_logo($vehicleMake, 50);
            })
            ->editColumn('description', function($vehicleMake) {
                if($vehicleMake->description)
                {
                    if($vehicleMake->description->content != '') return '<i class="far fa-check"></i>';
                }
                return '';
            })
            ->editColumn('featured_image', function($vehicleMake) {
                if($vehicleMake->description)
                {
                    if($vehicleMake->description->featured_image != '') return '<i class="far fa-check"></i>';
                }
                return '';
            })
            ->editColumn('country', function($vehicleMake) {

                if($vehicleMake->country)
                {
                    //return $vehicleMake->country->name;
                    return '<img src="'.url('/images/flags/'.$vehicleMake->country->flag).'">';
                }
                return '';

            })
            ->addColumn('action', function ($vehicleMake) {
                    return '<a href="'.url('admin/vehicle-models/'.$vehicleMake->slug).'" class="btn bg-primary btn-labeled" type="button"><b><i class="far fa-eye"></i></b> View Models</a>
                    &nbsp;<a href="'.url('admin/vehicle-make/'.$vehicleMake->slug.'/edit').'" class="btn bg-success btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                    &nbsp;<a href="'.url('admin/vehicle-make/'.$vehicleMake->slug.'/description').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Description</a>';
                
            })
            ->rawColumns(['logo', 'description', 'featured_image', 'country', 'action'])
            ->make(true);
    }

    public function removeLogo($id)
    {
        $this->vehicleTypeService->removeLogoPicture($id);
        return redirect('admin/vehicle-makes');
    }

    public function viewDescription($id)
    {
        $vehicleMake = \App\Models\VehicleMake::with('description')->where('slug', $id)->firstOrFail();
        if(!$vehicleMake->description)
        {
            return view('admin.VehicleMakes.descriptionCreate', compact('vehicleMake'));
        }
        return view('admin.VehicleMakes.descriptionUpdate', compact('vehicleMake'));
    }

    public function descriptionUpdate(Requests\Admin\VehicleMakeDescription $request, $id)
    {
        //dd($request);
        $vehicleMake = $this->vehicleTypeService->vehicleMakeDescription($request, $id);
        return redirect('admin/vehicle-make/'.$vehicleMake->slug.'/description');
    }

    public function descriptionAddFromWiki(Requests\Admin\WikiScrapeRequest $request, $id)
    {
        $vehicleMake = \App\Models\VehicleMake::with('description')->where('slug', $id)->firstOrFail();
        $hasDesc = $vehicleMake->description;
        $page = $this->wikiScraperService->getPageByTitle($request->wikititle);

        if(!$hasDesc)
        {
            $vehicleMake->description = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
            return view('admin.VehicleMakes.descriptionCreate', compact('vehicleMake'));
        }
        $vehicleMake->description->content = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
        return view('admin.VehicleMakes.descriptionUpdate', compact('vehicleMake'));
    }

    public function create()
    {
        $countrylist = Countries::pluck('name', 'id');
        $description = '';
        return view('admin.VehicleMakes.create', compact('countrylist', 'description'));
    }

    public function createAddFromWiki(Requests\Admin\WikiScrapeRequest $request)
    {
        $page = $this->wikiScraperService->getPageByTitle($request->wikititle);
        $description = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
        $countrylist = Countries::pluck('name', 'id');
        return view('admin.VehicleMakes.create', compact('countrylist', 'description'));
    }

    public function store(Requests\Admin\CreateVehicleMakeRequest $request)
    {
        $this->vehicleTypeService->createMake($request);
        return redirect('/admin/vehicle-makes');
    }

}
