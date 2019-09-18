<?php

namespace Quantum\base\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Services\ActivityLogService;

class CleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:activityClean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Up activity logs';
    /**
     * @var ActivityLogService
     */
    private $activityLogService;

    /**
     * Create a new command instance.
     *
     * @param ActivityLogService $activityLogService
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        parent::__construct();
        $this->activityLogService = $activityLogService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->activityLogService->clean(60);
    }
}