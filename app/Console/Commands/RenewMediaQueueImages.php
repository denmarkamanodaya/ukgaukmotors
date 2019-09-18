<?php

namespace App\Console\Commands;

use App\Services\AuctionImportService;
use Illuminate\Console\Command;

class RenewMediaQueueImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:renewImageMediaQueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Image array in queue';
    /**
     * @var AuctionImportService
     */
    private $auctionImportService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AuctionImportService $auctionImportService)
    {
        parent::__construct();
        $this->auctionImportService = $auctionImportService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->auctionImportService->getBrokenImages();
    }
}
