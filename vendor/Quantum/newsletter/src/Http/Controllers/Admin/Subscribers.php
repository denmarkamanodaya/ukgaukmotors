<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Http\Requests\CreateSubscriberRequest;
use Quantum\newsletter\Http\Requests\EditSubscriberRequest;
use Quantum\newsletter\Http\Requests\SubscriberSearchRequest;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\SubscriberService;

class Subscribers extends Controller
{
    /**
     * @var SubscriberService
     */
    private $subscriberService;
    /**
     * @var NewsletterService
     */
    private $newsletterService;

    public function __construct(SubscriberService $subscriberService, NewsletterService $newsletterService)
    {
        $this->subscriberService = $subscriberService;
        $this->newsletterService = $newsletterService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsArray = [0 => 'Any Newsletter'];
        $newsletters = $this->newsletterService->getAllNewslettersList()->toArray();
        $newsletters = $newsArray + $newsletters;
        $subscribers = $this->subscriberService->getAllSubscribers();
        return view('newsletter::admin/subscribers/index', compact('newsletters', 'subscribers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($selectedNewsletter=null)
    {
        if($selectedNewsletter) $selectedNewsletter = $this->newsletterService->getNewsletterBySlug($selectedNewsletter);
        $newsletters = $this->newsletterService->getAllNewsletters();
        return view('newsletter::admin/subscribers/create', compact('newsletters', 'selectedNewsletter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubscriberRequest $request)
    {
        $this->subscriberService->createSubscriber($request);
        return redirect('/admin/newsletter');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriber = $this->subscriberService->getSubscriberById($id);
        $newsletters[0] = 'Do Not Move';
        $newslettersList = $this->newsletterService->getAllNewslettersList()->toArray();
        $newsletters = $newsletters + $newslettersList;
        return view('newsletter::admin/subscribers/edit', compact('subscriber', 'newsletters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSubscriberRequest $request, $id)
    {
        if($updated = $this->subscriberService->updateSubscriber($request, $id))
        {
            return redirect('admin/newsletter/subscribers');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $curnewsletter = $this->newsletterService->getNewsletterBySlug($id, ['subscriberCount']);
        $curnewsletter->subscriberCount = 10;
        $newsletters[0] = 'Just Remove Subscribers';
        $newsletters = array_merge($newsletters,$this->newsletterService->getAllNewslettersList($curnewsletter->id)->toArray());
        return view('newsletter::admin/delete', compact('curnewsletter', 'newsletters'));
    }

    public function search(SubscriberSearchRequest $request)
    {
        $newsArray = [0 => 'Any Newsletter'];
        $newsletters = $this->newsletterService->getAllNewslettersList()->toArray();
        $newsletters = $newsArray + $newsletters;
        $subscribers = $this->subscriberService->searchSubscribers($request);
        return view('newsletter::admin/subscribers/index', compact('newsletters', 'subscribers'));
    }

    public function newsletterSubscribers($newsletter)
    {
        $newsArray = [0 => 'Any Newsletter'];
        $newsletters = $this->newsletterService->getAllNewslettersList()->toArray();
        $newsletters = $newsArray + $newsletters;
        $subscribers = $this->subscriberService->newsletterSubscribers($newsletter);
        return view('newsletter::admin/subscribers/index', compact('newsletters', 'subscribers'));
    }



}
