<?php

namespace App\Http\Controllers\Frontend;

use App\Services\DealerService;
use App\Services\RestrictUserService;
use App\Services\SeoService;
use Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Dealers extends Controller
{

    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var RestrictUserService
     */
    private $restrictUserService;
    /**
     * @var SeoService
     */
    private $seoService;

    public function __construct(DealerService $dealerService, RestrictUserService $restrictUserService, SeoService $seoService)
    {
        $this->dealerService = $dealerService;
        $this->restrictUserService = $restrictUserService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dealers = $this->dealerService->getDealers(true);
        $dealerCounties = $this->dealerService->getDealerCounty();
        array_unshift($dealerCounties, 'Choose');
        $searches = ['0', ' '];
        foreach ($searches as $search)
        {
            if(($key = array_search($search, $dealerCounties)) !== false) {
                unset($dealerCounties[$key]);
            }
	}

        // Seo
        $seoData = (object) array(
                'title'         => "Auctioneers Directory Search ALL auctioneers in the UK",
                'description'   => "FIND YOUR PERFECT MOTOR | Check out all used cars at auction at every auctioneer in the UK 300,000 Lots Daily",
        );
        $this->seoService->generic($seoData);

        return view('frontend.Dealers.index', compact('dealers', 'dealerCounties'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //limit car view
        if($this->restrictUserService->restrictView('displayedA')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedA');
        //end limit
        
        $dealer = $this->dealerService->getDealer($id);
        $latestVehicles = $this->dealerService->latestVehicles($dealer);
        $auctionDays = $this->dealerService->auctionDays($dealer);
        $dealerVehicleImages = $this->dealerService->dealerCarImages($dealer);
        $this->seoService->auctioneer($dealer);
        return view('frontend.Dealers.show', compact('dealer', 'latestVehicles', 'auctionDays', 'dealerVehicleImages'));
    }
}
