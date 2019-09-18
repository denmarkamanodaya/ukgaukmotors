<?php

namespace App\Http\Controllers\Admin;

use App\Services\ImportingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Importing extends Controller
{

    /**
     * @var ImportingService
     */
    private $importingService;

    public function __construct(ImportingService $importingService)
    {
        $this->importingService = $importingService;
    }

    public function features()
    {
        $this->importingService->features();
    }




}
