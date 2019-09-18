<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Models\MenuItems;
use Quantum\blog\Models\PostRevisions;
use Quantum\blog\Models\Posts;
use Quantum\page\Models\Page;
use Quantum\page\Models\PageRevisions;

class MigrateLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:migrateLive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update links from dev to live';

    protected $devUrl = 'http://gaukmotors.dev';
    protected $liveUrl = 'http://gaukmotors2.dev';

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
        $menuitems = MenuItems::all();
        foreach($menuitems as $menuitem)
        {
            $menuitem->url = str_replace($this->devUrl, $this->liveUrl, $menuitem->url);
            $menuitem->save();
        }

        $pages = Page::all();
        foreach($pages as $page)
        {
            $page->content = str_replace($this->devUrl, $this->liveUrl, $page->content);
            $page->preContent = str_replace($this->devUrl, $this->liveUrl, $page->preContent);
            $page->save();
        }

        $pages = PageRevisions::all();
        foreach($pages as $page)
        {
            $page->content = str_replace($this->devUrl, $this->liveUrl, $page->content);
            $page->preContent = str_replace($this->devUrl, $this->liveUrl, $page->preContent);
            $page->save();
        }

        $posts = Posts::all();
        foreach($posts as $post)
        {
            $post->content = str_replace($this->devUrl, $this->liveUrl, $post->content);
            $post->summary = str_replace($this->devUrl, $this->liveUrl, $post->summary);
            $post->save();
        }

        $posts = PostRevisions::all();
        foreach($posts as $post)
        {
            $post->content = str_replace($this->devUrl, $this->liveUrl, $post->content);
            $post->summary = str_replace($this->devUrl, $this->liveUrl, $post->summary);
            $post->save();
        }
    }
}
