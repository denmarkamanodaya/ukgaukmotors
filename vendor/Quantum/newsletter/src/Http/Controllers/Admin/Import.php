<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Http\Requests\CreateSubscriberRequest;
use Quantum\newsletter\Http\Requests\EditSubscriberRequest;
use Quantum\newsletter\Http\Requests\ImportSubscribersRequest;
use Quantum\newsletter\Http\Requests\SubscriberSearchRequest;
use Quantum\newsletter\Services\ImportService;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\SubscriberService;

class Import extends Controller
{
    /**
     * @var SubscriberService
     */
    private $subscriberService;
    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var ImportService
     */
    private $importService;

    public function __construct(SubscriberService $subscriberService, NewsletterService $newsletterService, ImportService $importService)
    {
        $this->subscriberService = $subscriberService;
        $this->newsletterService = $newsletterService;
        $this->importService = $importService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletterService->getAllNewslettersList()->toArray();
        return view('newsletter::admin/import/import', compact('newsletters'));
    }

    /**
     * Import the subscribers
     *
     * @return \Illuminate\Http\Response
     */
    public function doImport(ImportSubscribersRequest $request)
    {
        $imported = $this->importService->import($request);
        if($request->large_import && $request->large_import == 1){
            return redirect('/admin/newsletter/import/queued');
        }
        return view('newsletter::admin/import/imported', compact('imported'));
    }

    public function queued()
    {
        $queued = $this->importService->getAllQueued();
        return view('newsletter::admin/import/queued', compact('queued'));
    }


}
