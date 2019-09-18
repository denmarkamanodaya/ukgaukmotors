<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : VehicleVariants.php
 **/

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelVarient;
use App\Services\VehicleTypeService;
use Yajra\DataTables\Facades\DataTables;

class VehicleVariants extends Controller
{
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;

    public function __construct(VehicleTypeService $vehicleTypeService)
    {
        $this->vehicleTypeService = $vehicleTypeService;
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
            $vehicleModel = VehicleModel::where('id', $id)->firstOrFail();
            $vehicleMake = VehicleMake::where('id', $vehicleModel->vehicle_make_id)->first();
        }
        return view('admin.VehicleModelVariants.index', compact('vehicleModel', 'vehicleMake'));
    }

    public function data($make)
    {
            $modelRes = VehicleModel::where('id', $make)->firstOrFail();

            $vehicleVariants = \Cache::remember($modelRes->id.'-vehiclevariantsearch', 5, function() use($modelRes) {
                return \App\Models\VehicleModelVarient::with('vehiclemodel', 'description')->where('vehicle_model_id', $modelRes->id)->orderBy('id', 'ASC')->get();
            });
        


        return Datatables::of($vehicleVariants)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('description', function($vehicleVariant) {
                if($vehicleVariant->description)
                {
                    if($vehicleVariant->description->content != '') return '<i class="far fa-check"></i>';
                }
                return '';
            })
            ->addColumn('action', function ($vehicleVariant) {
                if($vehicleVariant->default == 0)
                {
                    return '<a href="'.url('admin/vehicle-model-variants/details/'.$vehicleVariant->id).'" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> Details</a>
                    &nbsp;<a href="'.url('admin/vehicle-model-variants/details/'.$vehicleVariant->id).'/setDefault" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-check"></i></b> Set as Default</a>
                    &nbsp;<a href="'.url('admin/vehicle-model-variants/'.$vehicleVariant->id.'/description').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Description</a>';
                } else {
                    return '<a href="'.url('admin/vehicle-model-variants/details/'.$vehicleVariant->id).'" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> Details</a>
                    &nbsp;<a href="'.url('admin/vehicle-model-variants/'.$vehicleVariant->id.'/description').'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Description</a>';
                }

            })
            ->editColumn('default', function ($vehicleVariant) {
                if($vehicleVariant->default == 0)
                {
                    return '';
                } else {
                    return '<i class="far fa-check"></i>';
                }

            })
            ->rawColumns(['description', 'action', 'default'])
            ->make(true);
    }

    public function details($id)
    {
        $variant = VehicleModelVarient::with('vehiclemodel')->where('id', $id)->firstOrFail();
        $vehicleMake = VehicleMake::where('id', $variant->vehiclemodel->vehicle_make_id)->firstOrFail();
        //dd($variant, $vehicleMake);
        return view('admin.VehicleModelVariants.details', compact('variant', 'vehicleMake'));
    }

    public function setdefault($id)
    {
        $variant = VehicleModelVarient::where('id', $id)->firstOrFail();
        VehicleModelVarient::where('vehicle_model_id', $variant->vehicle_model_id)->update([
            'default' => '0'
        ]);
        $variant->default = '1';
        $variant->save();
        \Flash::success('Success : Default has been Updated.');
        \Cache::forget($variant->vehicle_model_id.'-vehiclevariantsearch');
        return back();
    }

    public function viewDescription($id)
    {
        $vehicleVariant = \App\Models\VehicleModelVarient::with('vehiclemodel', 'description')->where('id', $id)->firstOrFail();
        $vehicleMake = VehicleMake::where('id', $vehicleVariant->vehiclemodel->vehicle_make_id)->firstOrFail();
        if(!$vehicleVariant->description)
        {
            return view('admin.VehicleModelVariants.descriptionCreate', compact('vehicleVariant', 'vehicleMake'));
        }
        return view('admin.VehicleModelVariants.descriptionUpdate', compact('vehicleVariant', 'vehicleMake'));
    }

    public function descriptionUpdate(Requests\Admin\VehicleMakeDescription $request, $id)
    {
        $vehicleVariant = $this->vehicleTypeService->vehicleModelVariantDescription($request, $id);
        return redirect('admin/vehicle-model-variants/'.$vehicleVariant->id.'/description');
    }
}