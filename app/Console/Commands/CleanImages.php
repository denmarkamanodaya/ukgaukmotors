<?php

namespace App\Console\Commands;

use App\Models\Vehicles;
use Illuminate\Console\Command;

class CleanImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:cleanImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired auction images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directories = array_map('basename', \File::directories(config('main.public_path').'/images/vehicle'));
        foreach ($directories as $directory)
        {
            //echo $directory. PHP_EOL;
            if(!Vehicles::where('id', $directory)->first())
            {
                $imageFolder = config('main.public_path').'/images/vehicle/'.$directory;
                @array_map('unlink', glob("$imageFolder/*.*"));
                @rmdir($imageFolder);
            }
        }
    }
}
