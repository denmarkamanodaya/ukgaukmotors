<?php

namespace Quantum\newsletter\Console\Commands;

use Illuminate\Console\Command;
use Quantum\activity\Services\ActivityLogService;
use Quantum\membership\Services\MembershipService;
use Quantum\newsletter\Services\ImportService;
use Quantum\newsletter\Services\MailerService;

class ImportQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:newsletterImportQueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the newsletter import queue';
    /**
     * @var ImportService
     */
    private $importService;


    public function __construct(ImportService $importService)
    {
        parent::__construct();

        $this->importService = $importService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->importService->runImportQueue();
    }
}