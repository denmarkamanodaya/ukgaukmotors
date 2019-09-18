<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : BlogSeoService.php
 **/

namespace App\Services;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class BookSeoService
{

    public function book($book)
    {
        if(isset($book->title) && $book->title != '') {
            SEOMeta::setTitle($book->title);
            OpenGraph::setTitle($book->title);
        }
        if(isset($book->meta->description) && $book->meta->description != '') {
            SEOMeta::setDescription($book->meta->description);
            OpenGraph::setDescription($book->meta->description);
        } else {
            if(isset($book->summary) && $book->summary != '') {
                SEOMeta::setDescription(strip_tags($book->summary));
                OpenGraph::setDescription(strip_tags($book->summary));
            }
        }
        if(isset($book->meta->keywords) && $book->meta->keywords != '') {
            SEOMeta::setKeywords($book->meta->keywords);
        }
        if(isset($book->front_cover) && $book->front_cover != '') {
            OpenGraph::addImage(url($book->front_cover));
        } else {
            if(isset($book->meta->featured_image) && $book->meta->featured_image != '') {
                OpenGraph::addImage(url($book->meta->featured_image));
            }
        }
        if(isset($book->meta->type)) {
            OpenGraph::setType('article');
        }

            if($book->area == 'public')
            {
                OpenGraph::setUrl(postLink($book, 'public'));
            }
            if($book->area == 'members')
            {
                OpenGraph::setUrl(postLink($book, 'members'));
            }


        if(config('app.name')) {
            OpenGraph::setSiteName(env('App_Name'));
        }

        if(isset($book->meta->robots)) {
            SEOMeta::addMeta('robots', $book->meta->robots, 'name');
        }

        OpenGraph::setArticle([
            'published_time' => $book->created_at,
            'author' => 'GAUK Motors',
            'section' => isset($book->category) ? $book->category->name : ''
        ]);

    }
}