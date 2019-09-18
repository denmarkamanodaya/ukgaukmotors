<?php

namespace Quantum\blog\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Services\ModulesService;
use Quantum\blog\Services\BlogService;

class Posts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:postPublish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Timed Posts';
    /**
     * @var BlogService
     */
    private $blogService;


    /**
     * Create a new command instance.
     *
     * @param BlogService $blogService
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
        $this->blogService->publishTimed();
    }
}
