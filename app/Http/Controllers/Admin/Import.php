<?php

namespace App\Http\Controllers\Admin;

use App\Services\AuctionImportService;
use App\Services\ImportingService;
use Quantum\base\Models\News;
use Quantum\page\Models\Page;
use Quantum\base\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Import extends Controller
{

    /**
     * @var AuctionImportService
     */
    private $auctionImportService;
    /**
     * @var ImportingService
     */
    private $importingService;

    public function __construct(AuctionImportService $auctionImportService, ImportingService $importingService)
    {
        $this->auctionImportService = $auctionImportService;
        $this->importingService = $importingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $this->auctionImportService->import();
        //return view('admin.dashboard', compact('news', 'totals'));
    }

    public function media()
    {
        $this->auctionImportService->process_media_queue();
        //return view('admin.dashboard', compact('news', 'totals'));
    }

    public function index()
    {
        return view('admin.Import.index');
    }

    public function features()
    {
        $this->importingService->features();
    }

    public function categories()
    {
        $this->importingService->categories();
    }

    public function dealers()
    {
        $this->importingService->dealers();
    }

    public function parsedDealers()
    {
        $this->importingService->parsedDealers();
    }

    public function getLots()
    {
        $this->importingService->getLots();
    }

}
