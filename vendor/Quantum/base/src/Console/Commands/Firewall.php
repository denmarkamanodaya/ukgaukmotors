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

class Firewall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:firewall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired blocks';
    /**
     * @var FirewallService
     */
    private $firewallService;


    /**
     * Create a new command instance.
     *
     * @param \Quantum\base\Module\Module $module
     */
    public function __construct(FirewallService $firewallService)
    {
        parent::__construct();
        $this->firewallService = $firewallService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->firewallService->cleanup();
    }
}