<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Firewall.php
 **/

namespace Quantum\base\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Services\FirewallService;
use Quantum\base\Services\MenuService;

class MenuIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:menuicons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Menu Icons To FA5';
    /**
     * @var MenuService
     */
    private $menuService;


    /**
     * Create a new command instance.
     *
     * @param \Quantum\base\Module\Module $module
     */
    public function __construct(MenuService $menuService)
    {
        parent::__construct();
        $this->menuService = $menuService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->menuService->updateMenuIcons();
    }
}