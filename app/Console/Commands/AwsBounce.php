<?php

namespace App\Console\Commands;

use App\Services\AwsService;
use Illuminate\Console\Command;

class AwsBounce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:processAwsBounces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process AWS Bounces';
    /**
     * @var AwsService
     */
    private $awsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AwsService $awsService)
    {
        parent::__construct();
        $this->awsService = $awsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->awsService->processBounces();
    }
}
