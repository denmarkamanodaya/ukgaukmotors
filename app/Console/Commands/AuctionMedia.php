<?php

namespace App\Console\Commands;

use App\Services\AuctionImportService;
use Illuminate\Console\Command;

class AuctionMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:importMedia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import auction media';
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
        #$this->auctionImportService->process_media_queue();
	$this->auctionImportService->insert_to_vehicle_media();
    }
}
