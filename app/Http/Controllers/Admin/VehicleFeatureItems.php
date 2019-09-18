<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Services\VehicleFeaturesService;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class VehicleFeatureItems extends Controller
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
    public function index($id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($id);
        $items = $this->vehicleFeaturesService->getFeatureitems($feature);
        return view('admin.VehicleFeatures.Items.index', compact('feature', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($id);
        $icons = fontAwesomeList();
        return view('admin.VehicleFeatures.Items.create', compact('icons', 'feature'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\VehicleFeatureRequest $request, $feature)
    {
        $feature = $this->vehicleFeaturesService->getFeature($feature);
        $this->vehicleFeaturesService->createFeaturesitems($request, $feature);
        return redirect('/admin/vehicle-features/'.$feature->slug.'/items');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($feature, $id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($feature);
        $item = $this->vehicleFeaturesService->getFeatureitem($feature, $id);
        $icons = fontAwesomeList();
        return view('admin.VehicleFeatures.Items.edit', compact('icons', 'feature', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $feature, $id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($feature);
        $this->vehicleFeaturesService->editFeatureItems($request, $id, $feature);
        return redirect('/admin/vehicle-features/'.$feature->slug.'/item/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($feature, $id)
    {
        $feature = $this->vehicleFeaturesService->getFeature($feature);
        $this->vehicleFeaturesService->deleteFeatureItems($id, $feature);
        return redirect('/admin/vehicle-features/'.$feature->slug.'/items');
    }

    public function savePosition(Requests\Admin\UpdateBookPagePositionRequest $request, $feature)
    {
        $feature = $this->vehicleFeaturesService->getFeature($feature);
        $this->vehicleFeaturesService->savePositionItems($request, $feature);
        return redirect('/admin/vehicle-features/'.$feature->slug.'/items');
    }
}
