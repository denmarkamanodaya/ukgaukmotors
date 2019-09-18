<?php

namespace App\Console\Commands;

use App\Services\VehicleService;
use Illuminate\Console\Command;

class VehicleSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:vehicleSlugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update to Slugs';
    /**
     * @var VehicleService
     */
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
        $this->vehicleService->updateToSlug();
    }
}
