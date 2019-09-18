<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Quantum\base\Models\MenuItems;
use Quantum\blog\Models\PostRevisions;
use Quantum\blog\Models\Posts;
use Quantum\page\Models\Page;
use Quantum\page\Models\PageRevisions;

class MigrateDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:migrateDev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update links from live to dev';

    protected $devUrl = 'http://gaukmotors.com';
    protected $liveUrl = 'http://gaukmotors2.com';

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
            $menuitem->url = str_replace($this->liveUrl, $this->devUrl, $menuitem->url);
            $menuitem->save();
        }

        $pages = Page::all();
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
