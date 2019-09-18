<?php

namespace Quantum\base\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Models\MenuItems;
use Quantum\blog\Models\PostRevisions;
use Quantum\blog\Models\Posts;
use Quantum\base\Models\Page;
use Quantum\base\Models\PageRevisions;

class ChangeUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:changeUrl {from} {to?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Url within main content areas';

    protected $devUrl;
    protected $liveUrl;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!filter_var($this->argument('from'), FILTER_VALIDATE_URL)) {
           exit;
        }
        $this->liveUrl = $this->argument('from');
        $this->devUrl = ($this->argument('to')) ? $this->argument('to') : '';
        //dd($this->liveUrl, $this->devUrl);
        $menuitems = MenuItems::all();
        foreach($menuitems as $menuitem)
        {
            $menuitem->url = str_replace($this->liveUrl, $this->devUrl, $menuitem->url);
            $menuitem->save();
        }

        $pages = Page::tenant()->get();
        foreach($pages as $page)
        {
            $page->content = str_replace($this->liveUrl, $this->devUrl, $page->content);
            $page->preContent = str_replace($this->liveUrl, $this->devUrl, $page->preContent);
            $page->save();
        }

        $pages = PageRevisions::all();
        foreach($pages as $page)
        {
            $page->content = str_replace($this->liveUrl, $this->devUrl, $page->content);
            $page->preContent = str_replace($this->liveUrl, $this->devUrl, $page->preContent);
            $page->save();
        }

        $posts = Posts::all();
        foreach($posts as $post)
        {
            $post->content = str_replace($this->liveUrl, $this->devUrl, $post->content);
            $post->summary = str_replace($this->liveUrl, $this->devUrl, $post->summary);
            $post->save();
        }

        $posts = PostRevisions::all();
        foreach($posts as $post)
        {
            $post->content = str_replace($this->liveUrl, $this->devUrl, $post->content);
            $post->summary = str_replace($this->liveUrl, $this->devUrl, $post->summary);
            $post->save();
        }
    }
}
