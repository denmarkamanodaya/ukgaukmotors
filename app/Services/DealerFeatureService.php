<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : AuctioneerFeatureService.php
 **/

namespace App\Services;


use App\Models\DealersFeatures;

class DealerFeatureService
{
    public function getFeatures()
    {
        $features = DealersFeatures::withCount('dealer')->orderBy('name', 'ASC')->get();
        return $features;
    }

    public function getFeatureBySlug($slug)
    {
        $feature = DealersFeatures::where('slug', $slug)->firstOrFail();
        return $feature;
    }

    public function updateFeature($request, $slug)
    {
        $feature = $this->getFeatureBySlug($slug);
        $feature->name = $request->name;
        $feature->slug = $request->slug;
        $feature->icon = isset($request->icon) ? $request->icon : null;
        $feature->save();

        flash('Feature has been updated.')->success();
        \Activitylogger::log('Admin - Updated Feature : '.$feature->name, $feature);
        return;
    }

    public function createFeature($request)
    {
        $feature = DealersFeatures::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'icon' => isset($request->icon) ? $request->icon : null,
        ]);

        flash('Feature has been created.')->success();
        \Activitylogger::log('Admin - Created Feature : '.$feature->name, $feature);
        return;
    }

    public function deleteFeature($slug)
    {
        $feature = $this->getFeatureBySlug($slug);
        if($feature->system == 1){
            flash('Feature can not be deleted.')->error();
            return;
        }
        $feature->delete();
        flash('Feature has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Feature : '.$feature->name, $feature);
        return;
    }
}