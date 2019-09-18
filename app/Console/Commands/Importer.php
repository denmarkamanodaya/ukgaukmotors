<?php

namespace App\Console\Commands;

use App\Services\ImportingService;
use Illuminate\Console\Command;

class Importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:importer {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Content Importer';
    /**
     * @var ImportingService
     */
    private $importingService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImportingService $importingService)
    {
        parent::__construct();
        $this->importingService = $importingService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $importType = $this->argument('type');

        switch ($importType)
        {
            case "features":
                $this->importingService->features();
                break;
            case "categories":
                $this->importingService->categories();
                break;
            case "dealers":
                $this->importingService->dealers();
                break;
            case "parsedDealers":
                $this->importingService->parsedDealers();
                break;
            case "getLots":
                $this->importingService->getLots();
                break;
        }

    }
}
