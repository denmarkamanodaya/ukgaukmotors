<?php

namespace App\Console\Commands;

use App\Services\VehicleToolsService;
use Illuminate\Console\Command;

class MatchVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:matchVehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'match makes to unlisted vehicles';
    /**
     * @var VehicleToolsService
     */
    private $vehicleToolsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VehicleToolsService $vehicleToolsService)
    {
        parent::__construct();
        $this->vehicleToolsService = $vehicleToolsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->vehicleToolsService->matchAllVehicles(2000);
    }
}
