<?php

namespace Quantum\base\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Services\ModulesService;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or update Quantum modules';

    /**
     * @var ModulesService
     */
    private $modulesService;

    /**
     * Create a new command instance.
     *
     * @param \Quantum\base\Module\Module $module
     */
    public function __construct(ModulesService $modulesService)
    {
        parent::__construct();
        $this->modulesService = $modulesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->modulesService->installModules();
    }
}
