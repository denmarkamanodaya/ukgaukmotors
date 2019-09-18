<?php

namespace App\Console\Commands;

use App\Models\VehicleMake;
use App\Services\AuctionCache;
use Illuminate\Console\Command;

class CleanVehicleMakes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:cleanMakes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unwanted vehicle makes';
    /**
     * @var AuctionCache
     */
    private $auctionCache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AuctionCache $auctionCache)
    {
        parent::__construct();
        $this->auctionCache = $auctionCache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vehicleMakes = VehicleMake::all();
        foreach($vehicleMakes as $vehicleMake)
        {
            if(!makeAllowed($vehicleMake->name)) $vehicleMake->delete();
        }
        $this->auctionCache->cacheClear();
    }
}
