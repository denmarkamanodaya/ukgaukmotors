<?php

namespace App\Http\Controllers\Admin;

use App\Services\VehicleFeaturesService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VehicleFeatures extends Controller
{

    /**
     * @var VehicleFeaturesService
     */
    private $vehicleFeaturesService;

    public function __construct(VehicleFeaturesService $vehicleFeaturesService)
    {
        $this->vehicleFeaturesService = $vehicleFeaturesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = $this->vehicleFeaturesService->getFeatures();
        return view('admin.VehicleFeatures.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $icons = fontAwesomeList();
        return view('admin.VehicleFeatures.create', compact('icons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\VehicleFeatureRequest $request)
    {
        $this->vehicleFeaturesService->createFeatures($request);
        return redirect('/admin/vehicle-features');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($id);
        $icons = fontAwesomeList();
        return view('admin.VehicleFeatures.edit', compact('icons', 'feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->vehicleFeaturesService->editFeature($request, $id);
        return redirect('/admin/vehicle-features/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vehicleFeaturesService->deleteFeature($id);
        return redirect('/admin/vehicle-features/');
    }

    public function savePosition(Requests\Admin\UpdateBookPagePositionRequest $request)
    {
        $this->vehicleFeaturesService->savePosition($request);
        return redirect('/admin/vehicle-features/');
    }
}
