<?php

namespace App\Console\Commands;

use App\Services\AuctionImportService;
use Illuminate\Console\Command;

class AuctionRemoveExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:expireAuctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removed Expired Auctions';
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
        $this->auctionImportService->removeExpired();
    }
}
