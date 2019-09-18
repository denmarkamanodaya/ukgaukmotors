<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : FeedService.php
 **/

namespace App\Services;


use Quantum\blog\Services\BlogService;

class FeedService
{
    private $config;

    public function __construct()
    {
        $this->config = config()->get('feed');
    }

    public function render($type)
    {
        $feed = \App::make("feed");
        if ($this->config['use_cache']) {
            $feed->setCache($this->config['cache_duration'], $this->config['cache_key']);
        }

        if (!$feed->isCached()) {
            $posts = $this->getFeedData();
            $feed->title = $this->config['feed_title'];
            $feed->description = $this->config['feed_description'];
            $feed->logo = $this->config['feed_logo'];
            $feed->link = url('/posts/feed');
            $feed->setDateFormat('datetime');
            $feed->lang = 'en';
            $feed->setShortening(true);
            $feed->setTextLimit(250);

            if (!empty($posts)) {
                $feed->pubdate = $posts[0]->created_at;
                foreach ($posts as $post) {
                    $link = postLink($post, '');

                    $author = "";
                    if(!empty($post->user)){
                        $author = $post->user->username;
                    }
                    // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                    $feed->add($post->title, $post->user->username, $link, $post->created_at, $post->summary, $post->content);
                }
            }
        }

        return $feed->render($type);
    }

    /**
     * Creating rss feed with our most recent posts.
     * The size of the feed is defined in feed.php config.
     *
     * @return mixed
     */
    private function getFeedData()
    {
        $blogService = new BlogService();
        $maxSize = $this->config['max_size'];
        $posts = $blogService->getPosts(['public']);
        return $posts;
    }
}