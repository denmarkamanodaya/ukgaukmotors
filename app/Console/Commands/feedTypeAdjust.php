<?php

namespace App\Console\Commands;

use App\Services\GarageService;
use Illuminate\Console\Command;

class feedTypeAdjust extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:feedtypeAdjust';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Duplicate feeds for classified';
    /**
     * @var GarageService
     */
    private $garageService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GarageService $garageService)
    {
        parent::__construct();
        $this->garageService = $garageService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->garageService->feedTypeAdjust();
    }
}
