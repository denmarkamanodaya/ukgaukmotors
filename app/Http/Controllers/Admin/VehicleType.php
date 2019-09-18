<?php

namespace App\Http\Controllers\Admin;

use App\Services\VehicleTypeService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleType extends Controller
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
    public function index()
    {
        return view('admin.VehicleTypes.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\CreateVehicleTypeRequest $request)
    {
        $this->vehicleTypeService->createType($request);
        return redirect('admin/vehicle-type');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicletype = \App\Models\VehicleType::where('slug', $id)->where('system', '0')->firstOrFail();
        return view('admin.VehicleTypes.edit', compact('vehicletype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\EditVehicleTypeRequest $request, $id)
    {
        $this->vehicleTypeService->editType($request, $id);
        return redirect('admin/vehicle-type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vehicleTypeService->deleteType($id);
        return redirect('admin/vehicle-type');
    }

    public function data()
    {
        $vehicleTypes = $this->vehicleTypeService->getCachedTypes();

        return Datatables::of($vehicleTypes)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->addColumn('action', function ($vehicleType) {
                if($vehicleType->system != 1){
                    return '<a href="'.url('admin/vehicle-type/'.$vehicleType->slug.'/edit').'" class="btn bg-primary btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>';
                }
            })
            ->make(true);
    }

    public function convert()
    {
        $this->vehicleTypeService->convertVehicles();
    }

    public function convertCarMake(Requests\Admin\ConvertCarMakeRequest $request,$id)
    {
        $this->vehicleTypeService->convertCarMake($request, $id);
        return redirect('/admin/posts');
    }

    public function convertCarMakeNew($id)
    {
        if($result = $this->vehicleTypeService->convertCarMakeNew($id))
        {
            return redirect('/admin/posts');
        }
        return back();
    }

    public function convertCarModel(Requests\Admin\ConvertCarModelRequest $request,$id)
    {
        $this->vehicleTypeService->convertCarModel($request, $id);
        return redirect('/admin/posts');
    }

    public function convertCarModelNew(Requests\Admin\ConvertCarModelNewRequest $request, $id)
    {
        if($result = $this->vehicleTypeService->convertCarModelNew($request, $id))
        {
            return redirect('/admin/posts');
        }
        return back();
    }
}
