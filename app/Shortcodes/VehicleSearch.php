<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : VehicleSearch.php
 **/

namespace App\Shortcodes;


use App\Services\DealerService;
use App\Services\VehicleService;
use Illuminate\Support\Facades\View;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class VehicleSearch
{
    
    public static function searchWidget(ShortcodeInterface $s)
    {
        $widgetType = $s->getParameter('type') ? $s->getParameter('type') : 'normal';
        $dealerService = new DealerService();
        $vehicleService = new VehicleService();
        $dealerList[0] = 'Company';
        $dealerList = array_merge($dealerList,$dealerService->dealerSelectList()->toArray());
        $dealerLocation[0] = 'Location';
        $dealerLocation = array_merge($dealerLocation,$dealerService->getDealerCounty());
        $vehicleMakes[0] = 'Make';
        $vehicleMakes = array_merge($vehicleMakes,$vehicleService->vehiclesMakeListCount());
        $vehicleModels[0] = 'Model';

        if($widgetType == 'dashboard')
        {
            $view = View::make('Shortcodes.searchWidgetDashboard', compact('dealerList', 'dealerLocation', 'vehicleMakes', 'vehicleModels'));
        } else {
            $view = View::make('Shortcodes.searchWidget', compact('dealerList', 'dealerLocation', 'vehicleMakes', 'vehicleModels'));
        }
        $widget = $view->render();
        return $widget;
    }
    
    public static function vehicleMakeList(ShortcodeInterface $s)
    {
        $rows = $s->getParameter('rows') ? $s->getParameter('rows') : '3';
        $vehicleService = new VehicleService();
        $vehicleMakes = $vehicleService->vehiclesMakeWithCount();
        $vehicleCount = $vehicleService->vehicleCount();

        //$vehicleMakes = $vehicleMakes->random(31);
        $sorted = $vehicleMakes->sortByDesc(function($item)
        {
            if(isset($item->vehiclesCount->aggregate)) return $item->vehiclesCount->aggregate;
            return 0;
        });

        $sorted = $sorted->reject(function ($item) {
            return $item->slug == 'unlisted';
        });

        $vehicleMakes = $sorted->take(31);
        $vehicleMakes = $vehicleMakes->sortBy(function($item)
        {
            return $item->name;
        });

        if(!is_numeric($rows)) $rows = 3;
        $perRow = ceil($vehicleMakes->count() / $rows);
        $cols = ceil(12 / $rows);

        $view = View::make('Shortcodes.vehicleMakeCount', compact('vehicleMakes', 'perRow', 'vehicleCount', 'cols'));
        $widget = $view->render();
        return $widget;
    }
    
    public static function vehicleEndingSoon(ShortcodeInterface $s)
    {
        $total = $s->getParameter('amount') ? $s->getParameter('amount') : '8';
        $type = $s->getParameter('type') ? $s->getParameter('type') : '1';

        if($type == 'auctions') $type = 1;
        if($type == 'classified') $type = 2;
        if($type == 'trade') $type = 3;

        if($total > 20) $total = 20;
        $vehicleService = new VehicleService();
        $vehicles = $vehicleService->endingSoon($type);
        $vehicles = $vehicles->take($total);
        $view = View::make('Shortcodes.vehicleEndingSoon', compact('vehicles', 'total'));
        $widget = $view->render();
        //echo $widget; exit;
        return $widget;
    }

    public static function vehicleLatest(ShortcodeInterface $s)
    {

        $total = $s->getParameter('amount') ? $s->getParameter('amount') : '8';
        $type = $s->getParameter('type') ? $s->getParameter('type') : '1';

        if($type == 'auctions') $type = 1;
        if($type == 'classified') $type = 2;
        if($type == 'trade') $type = 3;

        if($total > 20) $total = 20;

        $vehicleService = new VehicleService();
        $vehicles = $vehicleService->latestAdditions($type);

        $vehicles = $vehicles->take($total);
        $view = View::make('Shortcodes.vehicleLatest', compact('vehicles', 'total', 'type'));
        $widget = $view->render();
        return $widget;
    }
    
    public static function vehicleLatestWidget(ShortcodeInterface $s)
    {
        $total = $s->getParameter('amount') ? $s->getParameter('amount') : '8';
        $type = $s->getParameter('type') ? $s->getParameter('type') : '1';

        if($type == 'auctions') $type = 1;
        if($type == 'classified') $type = 2;
        if($type == 'trade') $type = 3;

        if($total > 20) $total = 20;

        $vehicleService = new VehicleService();
        $vehicles = $vehicleService->latestAdditions($type);

        $vehicles = $vehicles->take($total);

        $view = View::make('Shortcodes.vehicleLatestWidget', compact('vehicles', 'total', 'type'));
        $widget = $view->render();
        return $widget;
    }

    public static function vehicleEndingSoonWidget(ShortcodeInterface $s)
    {
        $total = $s->getParameter('amount') ? $s->getParameter('amount') : '8';
        $type = $s->getParameter('type') ? $s->getParameter('type') : '1';

        if($type == 'auctions') $type = 1;
        if($type == 'classified') $type = 2;
        if($type == 'trade') $type = 3;

        if($total > 20) $total = 20;

        $vehicleService = new VehicleService();
        $vehicles = $vehicleService->endingSoon($type);

        $vehicles = $vehicles->take($total);

        $view = View::make('Shortcodes.vehicleEndingSoonWidget', compact('vehicles', 'total'));
        $widget = $view->render();
        return $widget;
    }

}