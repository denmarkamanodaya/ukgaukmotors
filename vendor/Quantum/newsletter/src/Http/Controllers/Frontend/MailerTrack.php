<?php

namespace Quantum\newsletter\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Response;
use Quantum\newsletter\Services\MailerService;
use Quantum\newsletter\Services\NewsletterService;
use Illuminate\Http\Request;

class MailerTrack extends Controller
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var MailerService
     */
    private $mailerService;

    public function __construct(MailerService $mailerService, NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
        $this->mailerService = $mailerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $subcode=null)
    {
        // Create a 1x1 ttransparent pixel and return it
        $pixel = sprintf('%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%',71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);
        $response = Response::make($pixel, 200);
        $response->header('Content-type','image/gif');
        $response->header('Content-Length',42);
        $response->header('Cache-Control','private, no-cache, no-cache=Set-Cookie, proxy-revalidate');
        $response->header('Expires','Wed, 11 Jan 2000 12:59:00 GMT');
        $response->header('Last-Modified','Wed, 11 Jan 2006 12:59:00 GMT');
        $response->header('Pragma','no-cache');

        $this->mailerService->logOpen($id, $subcode);

        return $response;
    }


}
