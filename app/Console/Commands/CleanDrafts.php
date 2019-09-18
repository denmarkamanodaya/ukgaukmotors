<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Quantum\blog\Services\BlogService;

class CleanDrafts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:cleanDrafts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old post drafts.';
    /**
     * @var BlogService
     */
    private $blogService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BlogService $blogService)
    {
        parent::__construct();
        $this->blogService = $blogService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->blogService->cleanDrafts();
    }
}
