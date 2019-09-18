<?php

namespace App\Http\Controllers\Members;

use App\Services\DealerService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\SeoService;

class Dealers extends Controller
{

    /**
     * @var DealerService
     */
    private $dealerService;

    private $seoService;

    public function __construct(DealerService $dealerService,  SeoService $seoService)
    {
        $this->dealerService = $dealerService;
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
        $dealerList[0] = 'Select Auctioneer';
        $dealerList = array_merge($dealerList,$this->dealerService->dealerSelectList()->toArray());
        array_unshift($dealerCounties, 'Choose Location');
        $searches = ['0', ' '];
        foreach ($searches as $search)
        {
            if(($key = array_search($search, $dealerCounties)) !== false) {
                unset($dealerCounties[$key]);
            }
        }

    return view('members.Dealers.index', compact('dealers', 'dealerCounties', 'dealerList'));
    }

    public function search(Requests\Members\AuctioneerSearchRequest $request)
    {
        if($request->name == '' && $request->location == '0' && $request->auctioneer == '0') return redirect('/members/auctioneers');
        $dealers = $this->dealerService->searchDealers($request, true);
        $dealerCounties = $this->dealerService->getDealerCounty();
        $dealerList[0] = 'Select Auctioneer';
        $dealerList = array_merge($dealerList,$this->dealerService->dealerSelectList()->toArray());
        $searchName = $request->name;
        $searchLocation = $request->location;
        $searchAuctioneer = $request->auctioneer;
        if($request->auctioneer != '0')
        {
            $dealer = $this->dealerService->getDealer($request->auctioneer);
            $searchAuctioneer = $dealer->name;
        }

        array_unshift($dealerCounties, 'Choose Location');
        return view('members.Dealers.search', compact('dealers', 'dealerCounties', 'searchName', 'searchLocation', 'dealerList', 'searchAuctioneer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        \Auth::user()->load('garageFeed');
        $dealer = $this->dealerService->getDealer($id);
        $latestVehicles = $this->dealerService->latestVehicles($dealer);
        $auctionDays = $this->dealerService->auctionDays($dealer);
        $dealerVehicleImages = $this->dealerService->dealerCarImages($dealer);
        $this->seoService->auctioneer($dealer);

        return view('members.Dealers.show', compact('dealer', 'latestVehicles', 'auctionDays', 'dealerVehicleImages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
