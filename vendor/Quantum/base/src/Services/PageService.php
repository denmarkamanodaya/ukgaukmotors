<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : PageService.php
 **/

namespace Quantum\base\Services;

use Quantum\base\Models\Page;
use Cache;
use Quantum\base\Models\PageRevisions;

class PageService
{

    public function createPage($request)
    {

        $page = Page::create($request->all());
        $page->meta()->create($request->all());
        $this->syncRoles($page, $request);
        flash('Page has been created.')->success();
        \Activitylogger::log('Admin - Created Page : '.$page->title, $page);
        return $page;
    }

    public function roleArray($page)
    {
        $roleArray = [];
        foreach($page->roles as $role)
        {
            array_push($roleArray, $role->id);
        }
        return $roleArray;
    }

    public function updatePage($id, $request)
    {
        $page = Page::where('id', $id)->tenant()->firstOrFail();
        $this->saveRevision($page);
        $page->update($request->all());
        $page->meta->update($request->all());
        $this->syncRoles($page, $request);

        $this->clearPageCache($page->route, $page->area);
        flash('Page has been updated')->success();
        \Activitylogger::log('Admin - Updated Page : '.$page->title, $page);
        return $page;
    }

    private function syncRoles($page, $request)
    {
        if($page->area == 'public')
        {
            $page->roles()->detach();
        }
        else {
            if(is_array($request->roles)) {
                $page->roles()->sync($request->roles);
            } else {
                $page->roles()->detach();
            }
        }
    }

    public function deletePage($id)
    {
        $page = Page::where('id', $id)->tenant()->firstOrFail();
        $area = $page->area;
        $page->revisions()->delete();
        $page->delete();
        $this->clearPageCache($page->route, $page->area);
        \Activitylogger::log('Admin - Deleted Page : '.$page->title, $page);
        flash('Page has been deleted')->success();
        return $area;
    }

    public function getPageList($area = null, $addUrl = true)
    {
        $pageList = [];
        $pageList['Page List'] = ['Choose a page'];
        if($area) {
            $pages = Page::where('area', $area)->tenant()->orderBy('title', 'ASC')->get();
        } else {
        $pages = Page::orderBy('title', 'ASC')->tenant()->get();
        }
        foreach ($pages as $page)
        {
            $area = ($page->area == 'public') ? '' : $page->area;
            if($addUrl)
            {
                $route = url($area.'/'.$page->route);  
            } else {
                if($area == '')
                {
                    $route = '/'.$page->route;
                } else {
                    $route = '/'.$area.'/'.$page->route;
                }

            }
            
            $pageList[ucfirst($page->area)][$route] = ucfirst($page->title);
        }
        return $pageList;
    }

    private function clearPageCache($route, $area)
    {
        Cache::forget($area.'-'.$route);
    }

    public function pageWithCache($route, $area)
    {

        $page = Cache::rememberForever($area.'-'.$route, function() use($route, $area) {
            return \Quantum\base\Models\Page::with('meta')->where('route', $route)->Area($area)->Published()->tenant()->firstOrFail();
        });
        return $page;
    }

    private function saveRevision($page)
    {
        $revision = new PageRevisions($page->toArray());
        $page->revisions()->save($revision);

        $count = PageRevisions::where('pages_id', $page->id)->count();
        if($count > 5)
        {
            PageRevisions::where('pages_id', $page->id)->orderBy('id', 'asc')->first()->delete();
        }
    }

    public function getPostRevisionList($page)
    {
        return PageRevisions::select(['id', 'created_at'])->where('pages_id', $page->id)->orderBy('id','desc')->get();
    }

    public function getRevisionById($id, $pageId)
    {
        return PageRevisions::where('id', $id)->where('pages_id', $pageId)->firstOrFail();
    }

}