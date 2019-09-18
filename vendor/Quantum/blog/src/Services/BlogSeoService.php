<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : BlogSeoService.php
 **/

namespace Quantum\blog\Services;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class BlogSeoService
{

    public function post($post)
    {
        if(isset($post->title) && $post->title != '') {
        SEOMeta::setTitle($post->title);
	    #OpenGraph::setTitle($post->title);
		#SEOMeta::setTitle($post->meta->title);
		OpenGraph::setTitle($post->meta->meta_title);
        }
        if(isset($post->meta->description) && $post->meta->description != '') {
            SEOMeta::setDescription($post->meta->description);
            OpenGraph::setDescription($post->meta->description);
        } else {
            if(isset($post->summary) && $post->summary != '') {
                SEOMeta::setDescription(strip_tags($post->summary));
                OpenGraph::setDescription(strip_tags($post->summary));
            }
        }
        if(isset($post->meta->keywords) && $post->meta->keywords != '') {
            SEOMeta::setKeywords($post->meta->keywords);
        }
        if(isset($post->meta->featured_image) && $post->meta->featured_image != '') {
            OpenGraph::addImage(url($post->meta->featured_image));
        }
        if(isset($post->meta->type)) {
            OpenGraph::setType('article');
        }

            if($post->area == 'public')
            {
                OpenGraph::setUrl(postLink($post, 'public'));
            }
            if($post->area == 'members')
            {
                OpenGraph::setUrl(postLink($post, 'members'));
            }


        if(config('app.name')) {
            OpenGraph::setSiteName(config('app.name'));
        }

        if(isset($post->meta->robots)) {
            SEOMeta::addMeta('robots', $post->meta->robots, 'name');
        }

        $tags = [];
        if(isset($post->tags) && count($post->tags) > 0)
        {
            foreach($post->tags as $tag)
            {
                array_push($tags, $tag->name);
            }
        }

        OpenGraph::setArticle([
            'published_time' => $post->publish_on,
            'author' => $post->user->username,
            'section' => isset($post->category) ? $post->category->name : '',
            'tag' => $tags
        ]);

    }
}
