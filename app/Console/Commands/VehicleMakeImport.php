<?php

namespace App\Console\Commands;

use App\Services\VehicleTypeService;
use Illuminate\Console\Command;

class VehicleMakeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:importVehicleMake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import vehicle make, model, varients';
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VehicleTypeService $vehicleTypeService)
    {
        parent::__construct();
        $this->vehicleTypeService = $vehicleTypeService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->vehicleTypeService->importCsv();
    }
}
