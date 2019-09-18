<?php

namespace App\Http\Controllers\Frontend;

use App\Services\FeedService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Feeds extends Controller
{
    private $builder;
    /**
     * @var FeedService
     */
    private $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    //We're making rss default type
    public function getFeed($type = "atom")
    {
        if ($type === "rss" || $type === "atom") {
            return $this->feedService->render($type);
        }

        //If invalid feed requested, redirect home
        return redirect('/');
    }
}
