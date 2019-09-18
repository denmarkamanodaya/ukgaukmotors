<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Categories.php
 **/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateFeatureRequest;
use App\Http\Requests\Admin\UpdateFeatureRequest;
use App\Services\DealerFeatureService;

class DealerFeatures extends Controller
{

    /**
     * @var DealerFeatureService
     */
    private $dealerFeatureService;

    public function __construct(DealerFeatureService $dealerFeatureService)
    {
        $this->dealerFeatureService = $dealerFeatureService;
    }
    
    public function index()
    {
        $features = $this->dealerFeatureService->getFeatures();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('admin.Auctioneers.Features.index', compact('features', 'fajson'));
    }

    public function store(CreateFeatureRequest $request)
    {
        $this->dealerFeatureService->createFeature($request);
        return redirect('admin/dealers/features');
    }


    public function show($id)
    {
        $feature = $this->dealerFeatureService->getFeatureBySlug($id);
        $features = $this->dealerFeatureService->getFeatures();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('admin.Auctioneers.Features.manage', compact('feature', 'fajson', 'features'));
    }

    
    public function update(UpdateFeatureRequest $request, $id)
    {
        $this->dealerFeatureService->updateFeature($request, $id);
        return redirect('admin/dealers/features');
    }


    public function delete($id)
    {
        $this->dealerFeatureService->deleteFeature($id);
        return redirect('admin/dealers/features');
    }


}