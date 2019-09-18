<?php

namespace Quantum\newsletter\Console\Commands;

use Illuminate\Console\Command;
use Quantum\activity\Services\ActivityLogService;
use Quantum\membership\Services\MembershipService;
use Quantum\newsletter\Services\MailerService;

class CountOpened extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:newsletterCountOpened';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the stat collector for opened mail';
    /**
     * @var MailerService
     */
    private $mailerService;


    public function __construct(MailerService $mailerService)
    {
        parent::__construct();

        $this->mailerService = $mailerService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->mailerService->countOpened();
    }
}