<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : VehicleFeaturesService.php
 **/

namespace App\Services;


use App\Models\VehicleFeatureItem;
use App\Models\VehicleFeatures;

class VehicleFeaturesService
{

    public function getFeatures()
    {
        $features = VehicleFeatures::orderBy('position', 'ASC')->get();
        return $features;
    }

    public function getFeaturesListing()
    {
        $features = VehicleFeatures::with('items')->has('items')->orderBy('position', 'ASC')->get();
        return $features;
    }

    public function createFeatures($request)
    {
        $feature = VehicleFeatures::create([
            'name' => $request->name,
            'system' => 0,
            'position' => 99,
            'icon' => $request->icon
        ]);
        \Flash::success('Vehicle Feature has been created');
        \Activitylogger::log('Admin - Created Vehicle Feature : '.$feature->name, $feature);
    }

    public function getFeature($slug)
    {
        $feature = VehicleFeatures::where('slug', $slug)->firstOrFail();
        return $feature;
    }

    public function editFeature($request, $slug)
    {
        $feature = $this->getFeature($slug);
        $feature->name = $request->name;
        $feature->position = $request->position;
        $feature->icon = $request->icon;
        $feature->save();

        \Flash::success('Vehicle Feature has been updated');
        \Activitylogger::log('Admin - Updated Vehicle Feature : '.$feature->name, $feature);
    }

    public function deleteFeature($slug)
    {
        $feature = $this->getFeature($slug);
        $feature->items()->delete();
        $feature->delete();

        \Flash::success('Vehicle Feature has been deleted');
        \Activitylogger::log('Admin - Deleted Vehicle Feature : '.$feature->name, $feature);
    }

    public function savePosition($request)
    {
        $i=1;
        foreach ($request->position as $position)
        {
            VehicleFeatures::where('id', $position)->update([
                'position' => $i
            ]);
            $i++;
        }
        \Flash::success('Positions updated.');
    }

    public function getFeatureItems($feature)
    {
        $items = VehicleFeatureItem::where('vehicle_features_id', $feature->id)->orderBy('position', 'ASC')->get();
        return $items;
    }

    public function getFeatureItem($feature, $slug)
    {
        $item = VehicleFeatureItem::where('vehicle_features_id', $feature->id)->where('slug', $slug)->firstOrFail();
        return $item;
    }

    public function createFeaturesItems($request, $feature)
    {
        $item = VehicleFeatureItem::create([
            'vehicle_features_id' => $feature->id,
            'name' => $request->name,
            'system' => 0,
            'position' => 99,
            'icon' => $request->icon
        ]);
        \Flash::success('Vehicle Feature Item has been created');
        \Activitylogger::log('Admin - Created Vehicle Feature Item : '.$item->name, $item);
    }

    public function editFeatureItems($request, $slug, $feature)
    {
        $item = $this->getFeatureItem($feature, $slug);
        $item->name = $request->name;
        $item->position = $request->position;
        $item->icon = $request->icon;
        $item->save();

        \Flash::success('Vehicle Feature Item has been updated');
        \Activitylogger::log('Admin - Updated Vehicle Feature : '.$item->name, $item);
    }

    public function deleteFeatureItems($slug, $feature)
    {
        $item = $this->getFeatureItem($feature, $slug);
        $item->delete();

        \Flash::success('Vehicle Feature Item has been deleted');
        \Activitylogger::log('Admin - Deleted Vehicle Feature : '.$item->name, $item);
    }

    public function savePositionItems($request, $feature)
    {
        $i=1;
        foreach ($request->position as $position)
        {
            VehicleFeatureItem::where('vehicle_features_id', $feature->id)->where('id', $position)->update([
                'position' => $i
            ]);
            $i++;
        }
        \Flash::success('Positions updated.');
    }
}