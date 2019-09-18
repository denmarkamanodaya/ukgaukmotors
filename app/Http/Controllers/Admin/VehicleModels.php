<?php

namespace App\Http\Controllers\Admin;

use App\Models\VehicleMake;
use App\Services\WikiScraperService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\VehicleTypeService;
use Yajra\DataTables\Facades\DataTables;

class VehicleModels extends Controller
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
    public function index($id=null)
    {
        $search = false;
        if($id) {
            $search = VehicleMake::where('slug', $id)->firstOrFail();
        }
        return view('admin.VehicleModels.index', compact('search'));
    }

    public function data($make=null)
    {
        if($make)
        {
            $makeRes = VehicleMake::where('slug', $make)->firstOrFail();

            $vehicleModels = \Cache::remember($makeRes->slug.'-vehiclemodelsearch', 5, function() use($makeRes) {
                return \App\Models\VehicleModel::with('make', 'description')->where('vehicle_make_id', $makeRes->id)->orderBy('name', 'ASC')->get();
            });


        } else {
            $vehicleModels = \Cache::rememberForever('vehicleModels', function() {
                return \App\Models\VehicleModel::with('make', 'description')->orderBy('name', 'ASC')->get();
            });
        }


        return Datatables::of($vehicleModels)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('description', function($vehicleModel) {
                if($vehicleModel->description)
                {
                    if($vehicleModel->description->content != '') return '<i class="far fa-check"></i>';
                }
                return '';
            })
            ->editColumn('featured_image', function($vehicleModel) {
                if($vehicleModel->description)
                {
                    if($vehicleModel->description->featured_image != '') return '<i class="far fa-check"></i>';
                }
                return '';
            })
            ->addColumn('action', function ($vehicleModel) {
                if($vehicleModel->system == '0')
                {
                    return '<a href="'.url('admin/vehicles/'.$vehicleModel->id).'" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> View Vehicles</a>
                &nbsp;<a href="'.url('admin/vehicle-model-variants/'.$vehicleModel->id).'" class="btn bg-success btn-labeled" type="button"><b><i class="fas fa-taxi"></i></b> View Variants</a>
                &nbsp;<a href="'.url('admin/vehicle-model/'.$vehicleModel->id.'/description').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Description</a>
                &nbsp;<a href="'.url('admin/vehicle-model/'.$vehicleModel->id.'/edit').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                &nbsp;<a href="'.url('admin/vehicle-model/'.$vehicleModel->id.'/delete').'" class="btn bg-warning btn-labeled" type="button"><b><i class="far fa-times"></i></b> Delete</a>';
                }
                return '<a href="'.url('admin/vehicles/'.$vehicleModel->id).'" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> View Vehicles</a>
                &nbsp;<a href="'.url('admin/vehicle-model-variants/'.$vehicleModel->id).'" class="btn bg-success btn-labeled" type="button"><b><i class="fas fa-taxi"></i></b> View Variants</a>
                &nbsp;<a href="'.url('admin/vehicle-model/'.$vehicleModel->id.'/description').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Description</a>
                &nbsp;<a href="'.url('admin/vehicle-model/'.$vehicleModel->id.'/edit').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>';

            })
            ->rawColumns(['description', 'featured_image', 'action'])
            ->make(true);
    }

    public function viewDescription($id)
    {
        $vehicleModel = \App\Models\VehicleModel::with('description', 'make')->where('id', $id)->firstOrFail();

        if(!$vehicleModel->description)
        {
            return view('admin.VehicleModels.descriptionCreate', compact('vehicleModel'));
        }
        return view('admin.VehicleModels.descriptionUpdate', compact('vehicleModel'));
    }

    public function descriptionUpdate(Requests\Admin\VehicleMakeDescription $request, $id)
    {
        $vehicleModel = $this->vehicleTypeService->vehicleModelDescription($request, $id);
        return redirect('admin/vehicle-model/'.$vehicleModel->id.'/description');
    }

    public function destroy($id)
    {
        $vehicleModel = $this->vehicleTypeService->deleteModel($id);
        return redirect('admin/vehicle-models/'.$vehicleModel->make->slug);
    }

    public function edit($id)
    {
        $vehicleModel = $this->vehicleTypeService->editModel($id);
        return view('admin.VehicleModels.edit', compact('vehicleModel'));
    }

    public function update(Requests\Admin\EditVehicleMakeRequest $request, $id)
    {
        $vehicleModel = $this->vehicleTypeService->editModelUpdate($request, $id);

        if($vehicleModel)
        {
            return redirect('admin/vehicle-models/'.$vehicleModel->make->slug);
        }
        return back();
    }
    public function descriptionAddFromWiki(Requests\Admin\WikiScrapeRequest $request, $id)
    {
        $vehicleModel = \App\Models\VehicleModel::with('description', 'make')->where('id', $id)->firstOrFail();
        $hasDesc = $vehicleModel->description;
        $page = $this->wikiScraperService->getPageByTitle($request->wikititle);

        if(!$hasDesc)
        {
            $vehicleModel->description = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
            return view('admin.VehicleModels.descriptionCreate', compact('vehicleModel'));
        }
        $vehicleModel->description->content = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
        return view('admin.VehicleModels.descriptionUpdate', compact('vehicleModel'));
    }

    public function create($make)
    {
        $vehicleMake = VehicleMake::where('slug', $make)->firstOrFail();
        $description = '';
        return view('admin.VehicleModels.create', compact('vehicleMake', 'description'));
    }

    public function createAddFromWiki(Requests\Admin\WikiScrapeRequest $request, $make)
    {
        $vehicleMake = VehicleMake::where('slug', $make)->firstOrFail();
        $page = $this->wikiScraperService->getPageByTitle($request->wikititle);
        $description = $this->wikiScraperService->cleanWikiPage($page['parse']['text']['*']);
        return view('admin.VehicleModels.create', compact('vehicleMake', 'description'));
    }

    public function store(Requests\Admin\CreateVehicleModelRequest $request, $make)
    {
        $vehicleMake = VehicleMake::where('slug', $make)->firstOrFail();
        $this->vehicleTypeService->createModel($request, $vehicleMake);
        return redirect('/admin/vehicle-models/'.$vehicleMake->slug);
    }

}
