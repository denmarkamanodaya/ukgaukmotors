<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VehicleService;

class VehicleCounty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:vehicleCounty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all the vehicles county listing';

    private $vehicleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VehicleService $vehicleService)
    {
        parent::__construct();
        $this->vehicleService = $vehicleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->vehicleService->updateCounty();
    }
}
