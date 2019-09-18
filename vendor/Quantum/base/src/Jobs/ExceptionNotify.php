<?php

namespace Quantum\base\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Quantum\base\Services\ExceptionService;

class ExceptionNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var ExceptionService
     */
    private $exceptionService;

    protected $exception;

    /**
     * Create a new job instance.
     *
     * @param ExceptionService $exceptionService
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExceptionService $exceptionService)
    {
        $exceptionService->notify($this->exception);
    }
}
