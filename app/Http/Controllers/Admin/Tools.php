<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dealers;
use App\Services\AuctionImportService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Tools extends Controller
{

    /**
     * @var AuctionImportService
     */
    private $auctionImportService;

    public function __construct(AuctionImportService $auctionImportService)
    {
        $this->auctionImportService = $auctionImportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDealerCounty()
    {
        $dealers = Dealers::all();
        foreach ($dealers as $dealer)
        {
            $dealer->county = trim($dealer->county);
            $dealer->save();
        }
        \Artisan::call('cache:clear');
    }

    public function moveImageDir()
    {
        $this->auctionImportService->moveImageDir();
    }

}
