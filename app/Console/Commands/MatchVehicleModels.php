<?php

namespace App\Console\Commands;

use App\Services\VehicleToolsService;
use Illuminate\Console\Command;

class MatchVehicleModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:matchVehicleModels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'match models to vehicles that have a make but no model';
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
        $this->vehicleToolsService->matchAllVehiclesWithoutModels(2000);
    }
}
