<?php

namespace App\Console\Commands;

use App\Services\StuckScheduleService;
use Illuminate\Console\Command;

class StuckSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:stuckschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean stuck schedules';
    /**
     * @var StuckScheduleService
     */
    private $scheduleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StuckScheduleService $scheduleService)
    {
        parent::__construct();
        $this->scheduleService = $scheduleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->scheduleService->check();
    }
}
