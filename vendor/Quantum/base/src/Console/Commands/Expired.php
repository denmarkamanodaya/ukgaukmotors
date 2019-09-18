<?php

namespace Quantum\base\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Services\MembershipService;

class Expired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:expiredMembership';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired memberships';
    /**
     * @var MembershipService
     */
    private $membershipService;


    public function __construct(MembershipService $membershipService)
    {
        parent::__construct();

        $this->membershipService = $membershipService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->membershipService->checkExpired();
    }
}