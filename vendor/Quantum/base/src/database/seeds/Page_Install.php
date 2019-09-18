<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Page;

class Page_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$dashboard_page = Page::where('area', 'members')->where('route', 'dashboard')->tenant()->first())
        {
            $page = Page::create([
                'title' => 'Dashboard',
                'area' => 'members',
                'route' => 'dashboard',
                'status' => 'published',
                'content' => '[news]',
            ]);

            $page->meta()->create([
                'type' => 'website',
                'robots' => 'noindex, nofollow',
            ]);

            $page->roles()->sync([3]);

            \Cache::forget('site.settings');


            $page = \Quantum\base\Models\Page::create( [
                'title' => 'index' ,
                'subtitle' => '',
                'content' => '<p>Welcome to the demo page.</p>

<p>You can login <a href="/login">HERE</a></p>

<p>&nbsp;</p>

<p>This page can be edited within the members area.</p>',
                'area' => 'public',
                'status' => 'published',
                'route' => 'index',
            ] );

            $pageMeta = \Quantum\base\Models\PageMeta::create([
                'page_id' => $page->id,
                'featured_image' => '',
                'description' => '',
                'type' => '',
                'keywords' => '',
                'robots' => 'index, follow'
            ]);

            $page = \Quantum\base\Models\Page::create( [
                'title' => 'Message Received' ,
                'subtitle' => '',
                'content' => '<h2>Message Received</h2>

<p>&nbsp;</p>

<p>Thank You, we have received your message and will be in contact with you as soon as possible.</p>',
                'area' => 'public',
                'status' => 'published',
                'route' => 'message-received',
            ] );

            $pageMeta = \Quantum\base\Models\PageMeta::create([
                'page_id' => $page->id,
                'featured_image' => '',
                'description' => '',
                'type' => '',
                'keywords' => '',
                'robots' => 'noindex, nofollow'
            ]);

        }

    }
}
