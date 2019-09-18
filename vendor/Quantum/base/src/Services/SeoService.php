<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : SeoService.php
 **/

namespace Quantum\base\Services;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class SeoService
{

    public function page($page)
    {
        if(isset($page->title) && $page->title != '') {
        SEOMeta::setTitle($page->title);
	    #OpenGraph::setTitle($page->title);
 	    #SEOMeta::setTitle($page->meta->title);
	    OpenGraph::setTitle($page->meta->meta_title);
        }
        if(isset($page->meta->description) && $page->meta->description != '') {
            SEOMeta::setDescription($page->meta->description);
            OpenGraph::setDescription($page->meta->description);
        }
        if(isset($page->meta->keywords) && $page->meta->keywords != '') {
            SEOMeta::setKeywords($page->meta->keywords);
        }
        if(isset($page->meta->featured_image)  && $page->meta->featured_image != '') {
            OpenGraph::addImage(url($page->meta->featured_image));
        }
        if(isset($page->meta->type)) {
            OpenGraph::setType($page->meta->type);
        }
        if(isset($page->route)) {
            if($page->area == 'public')
            {
                OpenGraph::setUrl(url($page->route));
            }
            if($page->area == 'members')
            {
                OpenGraph::setUrl(url('members/'.$page->route));
            }
        }

        if(config('app.name')) {
            OpenGraph::setSiteName(config('app.name'));
        }

        if(isset($page->meta->robots)) {
            SEOMeta::addMeta('robots', $page->meta->robots, 'name');
        }

    }

}
