<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Firewall.php
 **/

namespace Quantum\calendar\Console\Commands;

use Illuminate\Console\Command;
use Quantum\calendar\Services\CalendarService;

class CalendarDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:calendarDaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the daily calendar routines';
    /**
     * @var CalendarService
     */
    private $calendarService;


    /**
     * Create a new command instance.
     *
     * @param \Quantum\calendar\Module\Module $module
     */
    public function __construct(CalendarService $calendarService)
    {
        parent::__construct();
        $this->calendarService = $calendarService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->calendarService->dailyUpdate();
    }
}